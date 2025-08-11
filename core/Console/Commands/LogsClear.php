<?php

namespace Rivulet\Console\Commands;

use Rivulet\Rivulet;

class LogsClear {
    protected $app;

    public function __construct(Rivulet $app) {
        $this->app = $app;
    }

    public function execute($args) {
        $logDir = $this->app->basePath('storage/logs');
        $files = glob($logDir . '/*.log');
        foreach ($files as $file) {
            unlink($file);
        }
        echo "Logs cleared\n";
    }
}