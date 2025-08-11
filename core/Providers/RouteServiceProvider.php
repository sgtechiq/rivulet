<?php
namespace Rivulet\Providers;

use Rivulet\Routing\Router;

/**
 * Route Service Provider
 *
 * Registers the router service and loads application routes.
 * Provides the core routing functionality for the framework.
 */
class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register router service binding
     *
     * Binds the router implementation to the container:
     * - Makes service available via 'router' alias
     * - Injects application instance into router
     */
    public function register()
    {
        $this->app->bind('router', function ($app) {
            return new Router($app);
        });
    }

    /**
     * Bootstrap route loading
     *
     * Loads application routes after all service registrations:
     * - Ensures all dependencies are available
     * - Processes route definitions
     */
    public function boot()
    {
        $this->app->make('router')->loadRoutes();
    }
}
