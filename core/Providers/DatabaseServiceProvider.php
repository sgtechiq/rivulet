<?php

namespace Rivulet\Providers;

use Rivulet\Database\Connection;

class DatabaseServiceProvider extends ServiceProvider {
    public function register() {
        // Bind connection resolver
        $this->app->bind('db.connection', function ($app, $name = 'default') {
            return Connection::get($name);
        });
    }

    public function boot() {
        // Nothing needed
    }
}