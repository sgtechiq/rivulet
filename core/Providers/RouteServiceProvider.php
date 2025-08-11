<?php

namespace Rivulet\Providers;

use Rivulet\Routing\Router;

class RouteServiceProvider extends ServiceProvider {
    public function register() {
        $this->app->bind('router', function ($app) {
            return new Router($app);
        });
    }

    public function boot() {
        // Load routes here after all registrations
        $this->app->make('router')->loadRoutes();
    }
}