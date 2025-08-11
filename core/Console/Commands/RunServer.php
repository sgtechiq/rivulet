<?php

namespace Rivulet\Console\Commands;

use Rivulet\Rivulet;

class RunServer {
    protected $app;

    public function __construct(Rivulet $app) {
        $this->app = $app;
    }

    public function execute($args) {
        $host = $args[0] ?? 'localhost:8000';
        echo "Starting server on http://{$host}\n";
        exec("php -S {$host} -t public");
    }
}