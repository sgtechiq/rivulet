<?php
namespace Rivulet\Providers;

use Rivulet\Views\View;

/**
 * Views Service Provider
 *
 * Registers the view service with the application container.
 * Provides template rendering capabilities throughout the application.
 */
class ViewsServiceProvider extends ServiceProvider
{
    /**
     * Register view service binding
     *
     * Binds the view implementation to the container:
     * - Makes service available via 'view' alias
     * - Injects application instance into view service
     */
    public function register()
    {
        $this->app->bind('view', function ($app) {
            return new View($app);
        });
    }
}
