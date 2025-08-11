<?php

namespace Rivulet\Providers;

use Rivulet\Queue\Queue;
use Rivulet\Database\Migrations\Runner;

class QueueServiceProvider extends ServiceProvider {
    public function register() {
        $this->app->bind('queue', function ($app) {
            return new Queue($app);
        });
    }

    public function boot() {
        // Auto-migrate jobs table if not exists
        $runner = new Runner();
        // Check if table exists, if not migrate specific file
        // Placeholder: assume user runs migrate, or add check
    }
}