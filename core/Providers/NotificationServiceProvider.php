<?php

namespace Rivulet\Providers;

use Rivulet\Notifications\Notification;

class NotificationServiceProvider extends ServiceProvider {
    public function register() {
        $this->app->bind('notification', function ($app) {
            return new Notification($app);
        });
    }

    public function boot() {
        // Check env for enabled channels (optional logging if empty)
    }
}