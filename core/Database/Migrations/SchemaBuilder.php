<?php

namespace Rivulet\Database\Migrations;

use PDO;
use Rivulet\Database\Migrations\CreateTableOperation;
use Rivulet\Database\Migrations\DropTableOperation;
use Rivulet\Database\Migrations\AlterTableOperation;

class SchemaBuilder {
    use CreateTableOperation;
    use DropTableOperation;
    use AlterTableOperation;

    protected $pdo;
    protected $queries = [];

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function execute() {
        foreach ($this->queries as $query) {
            $this->pdo->exec($query);
        }
        $this->queries = [];
    }
}