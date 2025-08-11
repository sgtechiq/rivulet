<?php

namespace Rivulet\Database;

use PDO;
use Rivulet\Database\Operations\SelectOperation;
use Rivulet\Database\Operations\InsertOperation;
use Rivulet\Database\Operations\UpdateOperation;
use Rivulet\Database\Operations\DeleteOperation;

class QueryBuilder {
    use SelectOperation;
    use InsertOperation;
    use UpdateOperation;
    use DeleteOperation;

    protected $pdo;
    protected $table;

    public function __construct(PDO $pdo, $table) {
        $this->pdo = $pdo;
        $this->table = $table;
        $this->reset(); // From SelectOperation
    }
}