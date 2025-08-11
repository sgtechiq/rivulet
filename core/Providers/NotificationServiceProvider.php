<?php
namespace Rivulet\Providers;

use Rivulet\Notifications\Notification;

/**
 * Notification Service Provider
 *
 * Registers the notification service binding with the application container.
 * Makes the notification service available throughout the application.
 */
class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Register notification service binding
     *
     * Binds a closure that returns new Notification instances:
     * - Makes service available via 'notification' alias
     * - Injects application instance into notification service
     */
    public function register()
    {
        $this->app->bind('notification', function ($app) {
            return new Notification($app);
        });
    }

    /**
     * Boot notification service
     *
     * Optional channel configuration check:
     * - Can verify enabled notification channels
     * - May log warnings if no channels configured
     */
    public function boot()
    {
        // Check env for enabled channels (optional logging if empty)
    }
}
