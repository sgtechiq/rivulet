<?php

namespace Rivulet\Console\Commands;

use Rivulet\Rivulet;
use Rivulet\Routing\Router;

class RoutesCache {
    protected $app;

    public function __construct(Rivulet $app) {
        $this->app = $app;
    }

    public function execute($args) {
        $router = $this->app->make('router');
        $router->loadRoutes(); // Forces caching
        echo "Routes cached\n";
    }
}