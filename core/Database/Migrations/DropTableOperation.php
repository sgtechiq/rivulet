<?php

namespace Rivulet\Database\Migrations;

trait DropTableOperation {
    public function dropTable($table) {
        $this->queries[] = "DROP TABLE IF EXISTS {$table}";
    }
}