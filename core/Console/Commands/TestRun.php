<?php

namespace Rivulet\Console\Commands;

use Rivulet\Rivulet;

class TestRun {
    protected $app;

    public function __construct(Rivulet $app) {
        $this->app = $app;
    }

    public function execute($args) {
        $file = $args[0] ?? '';
        exec("vendor/bin/phpunit {$file}", $output);
        echo implode("\n", $output) . "\n";
    }
}