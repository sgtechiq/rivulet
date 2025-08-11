<?php

namespace Rivulet\Database\Operations;

trait InsertOperation {
    public function insert($data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $query = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(array_values($data));
        return $this->pdo->lastInsertId();
    }
}