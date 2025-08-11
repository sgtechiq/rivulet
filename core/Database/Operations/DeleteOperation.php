<?php

namespace Rivulet\Database\Operations;

trait DeleteOperation {
    public function delete() {
        $query = "DELETE FROM {$this->table}";
        $whereClause = $this->buildWhere();
        if ($whereClause) {
            $query .= ' WHERE ' . $whereClause;
        }
        $stmt = $this->pdo->prepare($query);
        $this->bindWhere($stmt);
        $stmt->execute();
        $rows = $stmt->rowCount();
        $this->reset();
        return $rows;
    }
}