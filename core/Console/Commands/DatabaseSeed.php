<?php

namespace Rivulet\Console\Commands;

use Rivulet\Rivulet;
use Rivulet\Database\Migrations\Runner;

class DatabaseSeed {
    protected $app;

    public function __construct(Rivulet $app) {
        $this->app = $app;
    }

    public function execute($args) {
        $runner = new Runner($this->app);
        $runner->seed();
        echo "Database seeded\n";
    }
}