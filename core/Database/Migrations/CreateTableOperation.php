<?php

namespace Rivulet\Database\Migrations;

trait CreateTableOperation {
    public function createTable($table, $callback) {
        $columns = [];
        $addColumn = function ($column, $type, $options = []) use (&$columns) {
            $colDef = "{$column} {$type}";
            if (isset($options['nullable']) && $options['nullable']) {
                $colDef .= ' NULL';
            } else {
                $colDef .= ' NOT NULL';
            }
            if (isset($options['default'])) {
                $colDef .= " DEFAULT '{$options['default']}'";
            }
            if (isset($options['auto_increment']) && $options['auto_increment']) {
                $colDef .= ' AUTO_INCREMENT';
            }
            if (isset($options['primary_key']) && $options['primary_key']) {
                $colDef .= ' PRIMARY KEY';
            }
            $columns[] = $colDef;
        };
        $callback($addColumn);
        $query = "CREATE TABLE {$table} (" . implode(', ', $columns) . ")";
        $this->queries[] = $query;
    }
}