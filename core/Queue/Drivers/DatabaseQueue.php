<?php
namespace Rivulet\Queue\Drivers;

use Rivulet\Database\Connection;

class DatabaseQueue
{
    protected $config;
    protected $table;

    public function __construct($config)
    {
        $this->config = $config;
        $this->table  = $config['table'];
    }

    public function push($queue, $payload)
    {
        $db   = Connection::get();
        $stmt = $db->prepare("INSERT INTO {$this->table} (queue, payload, attempts, available_at, created_at) VALUES (?, ?, 0, ?, ?)");
        $stmt->execute([$queue, $payload, time(), time()]);
    }

    public function pop($queue)
    {
        $db = Connection::get();
        $db->beginTransaction();
        $stmt = $db->prepare("SELECT * FROM {$this->table} WHERE queue = ? AND available_at <= ? AND reserved_at IS NULL ORDER BY id ASC LIMIT 1 FOR UPDATE SKIP LOCKED");
        $stmt->execute([$queue, time()]);
        $job = $stmt->fetch();
        if ($job) {
            $updateStmt = $db->prepare("UPDATE {$this->table} WHERE id = ? SET attempts = attempts + 1, reserved_at = ?");
            $updateStmt->execute([$job['id'], time()]);
        }
        $db->commit();
        return $job;
    }

    public function delete($id)
    {
        $db   = Connection::get();
        $stmt = $db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function fail($jobData, $error)
    {
        $db   = Connection::get();
        $stmt = $db->prepare("INSERT INTO {$this->config['failed_table']} (queue, payload, attempts, error, failed_at) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$jobData['queue'], $jobData['payload'], $jobData['attempts'], $error, time()]);
        $this->delete($jobData['id']);
    }

    public function retry($jobData, $attempts, $availableAt)
    {
        $db   = Connection::get();
        $stmt = $db->prepare("UPDATE {$this->table} SET attempts = ?, reserved_at = NULL, available_at = ? WHERE id = ?");
        $stmt->execute([$attempts, $availableAt, $jobData['id']]);
    }
}
