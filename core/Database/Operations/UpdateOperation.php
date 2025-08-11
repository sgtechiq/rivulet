<?php

namespace Rivulet\Database\Operations;

trait UpdateOperation {
    public function update($data) {
        $set = [];
        foreach ($data as $column => $value) {
            $set[] = "{$column} = ?";
        }
        $query = "UPDATE {$this->table} SET " . implode(', ', $set);
        $whereClause = $this->buildWhere();
        if ($whereClause) {
            $query .= ' WHERE ' . $whereClause;
        }
        $stmt = $this->pdo->prepare($query);
        $values = array_values($data);
        $this->bindWhere($stmt, count($values));
        $stmt->execute(array_merge($values, $this->getWhereValues()));
        $rows = $stmt->rowCount();
        $this->reset();
        return $rows;
    }
}