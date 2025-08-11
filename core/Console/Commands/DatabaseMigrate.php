<?php

namespace Rivulet\Console\Commands;

use Rivulet\Rivulet;
use Rivulet\Database\Migrations\Runner;

class DatabaseMigrate {
    protected $app;

    public function __construct(Rivulet $app) {
        $this->app = $app;
    }

    public function execute($args) {
        $runner = new Runner($this->app);
        $runner->migrate();
        echo "Migrations run\n";
    }
}

// Similar for seed, rollback (add rollback method to Runner: reverse migrations)