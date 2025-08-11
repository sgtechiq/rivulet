<?php

namespace Rivulet\Providers;

use Rivulet\Http\Client;

class HttpClientServiceProvider extends ServiceProvider {
    public function register() {
        $this->app->bind('http', function ($app) {
            return new Client();
        });
    }
}