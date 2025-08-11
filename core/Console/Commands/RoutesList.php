<?php

namespace Rivulet\Console\Commands;

use Rivulet\Rivulet;
use Rivulet\Routing\Router;

class RoutesList {
    protected $app;

    public function __construct(Rivulet $app) {
        $this->app = $app;
    }

    public function execute($args) {
        $router = $this->app->make('router');
        $router->loadRoutes(); // Ensure loaded
        foreach ($router->routes as $route) {
            echo "{$route->method} {$route->uri} -> " . (is_array($route->action) ? $route->action[0] . '@' . $route->action[1] : 'Closure') . "\n";
        }
    }
}