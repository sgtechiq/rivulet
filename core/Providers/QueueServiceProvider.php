<?php
namespace Rivulet\Providers;

use Rivulet\Queue\Queue;

/**
 * Queue Service Provider
 *
 * Registers the queue service and optionally sets up required database tables.
 */
class QueueServiceProvider extends ServiceProvider
{
    /**
     * Register the queue service
     *
     * Binds the Queue implementation to the container.
     */
    public function register(): void
    {
        $this->app->bind('queue', function ($app) {
            return new Queue($app);
        });
    }

    /**
     * Bootstrap queue service
     *
     * Optionally sets up database tables if using database queue driver.
     * Note: Actual migration should be handled via CLI command for production.
     */
    public function boot(): void
    {

    }
}
