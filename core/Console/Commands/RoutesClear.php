<?php

namespace Rivulet\Console\Commands;

use Rivulet\Rivulet;

class RoutesClear {
    protected $app;

    public function __construct(Rivulet $app) {
        $this->app = $app;
    }

    public function execute($args) {
        $cacheFile = $this->app->basePath('storage/cache/routes.cache');
        if (file_exists($cacheFile)) {
            unlink($cacheFile);
            echo "Routes cache cleared\n";
        } else {
            echo "No routes cache to clear\n";
        }
    }
}