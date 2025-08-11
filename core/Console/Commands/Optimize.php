<?php

namespace Rivulet\Console\Commands;

use Rivulet\Rivulet;

class Optimize {
    protected $app;

    public function __construct(Rivulet $app) {
        $this->app = $app;
    }

    public function execute($args) {
        // Clear cache, logs, routes cache, etc.
        (new CacheClear($this->app))->execute([]);
        (new LogsClear($this->app))->execute([]);
        (new RoutesClear($this->app))->execute([]);
        echo "Optimized\n";
    }
}