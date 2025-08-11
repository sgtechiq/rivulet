<?php

namespace Rivulet\Providers;

use Rivulet\Views\View;

class ViewsServiceProvider extends ServiceProvider {
    public function register() {
        $this->app->bind('view', function ($app) {
            return new View($app);
        });
    }
}