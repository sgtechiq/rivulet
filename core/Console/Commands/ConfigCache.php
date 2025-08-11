<?php

namespace Rivulet\Console\Commands;

use Rivulet\Rivulet;

class ConfigCache {
    protected $app;

    public function __construct(Rivulet $app) {
        $this->app = $app;
    }

    public function execute($args) {
        $this->app->loadConfigs(); // Forces caching
        echo "Configs cached\n";
    }
}