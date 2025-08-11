<?php

namespace Rivulet\Providers;

use Rivulet\Filesystem\Filesystem;

class FilesystemServiceProvider extends ServiceProvider {
    public function register() {
        $this->app->bind('filesystem', function ($app) {
            return new Filesystem($app);
        });
    }
}