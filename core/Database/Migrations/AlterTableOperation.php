<?php

namespace Rivulet\Database\Migrations;

trait AlterTableOperation {
    public function alterTable($table, $callback) {
        $alterations = [];
        $callback(function ($action, $column, $type = null, $options = []) use (&$alterations) {
            switch ($action) {
                case 'add':
                    $colDef = "{$column} {$type}";
                    if (isset($options['after'])) {
                        $colDef .= " AFTER {$options['after']}";
                    }
                    $alterations[] = "ADD COLUMN {$colDef}";
                    break;
                case 'drop':
                    $alterations[] = "DROP COLUMN {$column}";
                    break;
                case 'modify':
                    $alterations[] = "MODIFY COLUMN {$column} {$type}";
                    break;
                case 'rename':
                    $alterations[] = "RENAME COLUMN {$column} TO {$options['to']}";
                    break;
                // Add more like add index, drop index, etc.
            }
        });
        if (!empty($alterations)) {
            $query = "ALTER TABLE {$table} " . implode(', ', $alterations);
            $this->queries[] = $query;
        }
    }
}