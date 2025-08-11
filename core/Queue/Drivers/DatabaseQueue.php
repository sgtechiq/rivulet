<?php

namespace Rivulet\Queue\Drivers;

use Rivulet\Database\Connection;

class DatabaseQueue {
    protected $config;
    protected $table;

    public function __construct($config) {
        $this->config = $config;
        $this->table = $config['table'];
    }

    public function push($queue, $payload) {
        $db = Connection::get();
        $stmt = $db->prepare("INSERT INTO {$this->table} (queue, payload, attempts, available_at, created_at) VALUES (?, ?, 0, ?, ?)");
        $stmt->execute([$queue, $payload, time(), time()]);
    }

    public function pop($queue) {
        $db = Connection::get();
        $db->beginTransaction();
        $stmt = $db->prepare("SELECT * FROM {$this->table} WHERE queue = ? AND available_at <= ? AND reserved_at IS NULL ORDER BY id ASC LIMIT 1 FOR UPDATE");
        $stmt->execute([$queue, time()]);
        $job = $stmt->fetch();
        if ($job) {
            $updateStmt = $db->prepare("UPDATE {$this->table} WHERE id = ? SET attempts = attempts + 1, reserved_at = ?");
            $updateStmt->execute([$job['id'], time()]);
        }
        $db->commit();
        return $job;
    }
}