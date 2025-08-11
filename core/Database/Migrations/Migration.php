<?php

namespace Rivulet\Database\Migrations;

use Rivulet\Database\Connection;

abstract class Migration {
    protected $connection = 'default';

    abstract public function up();

    abstract public function down();

    protected function executeSchema($callback) {
        $builder = new SchemaBuilder(Connection::get($this->connection));
        $callback($builder);
        $builder->execute();
    }
}