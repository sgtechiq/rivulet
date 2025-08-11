<?php

namespace Rivulet\Providers;

use Rivulet\Session\Session;

class SessionServiceProvider extends ServiceProvider {
    public function register() {
        $this->app->bind('session', function ($app) {
            return new Session($app->getConfig('session'));
        });
    }

    public function boot() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}