<?php

namespace Rivulet\Providers;

use Rivulet\Cookies\Cookies;

class CookiesServiceProvider extends ServiceProvider {
    public function register() {
        $this->app->bind('cookie', function ($app) {
            return new Cookies($app->getConfig('cookies'));
        });
    }
}