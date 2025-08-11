<?php
namespace Rivulet\Providers;

use Rivulet\Session\Session;

/**
 * Session Service Provider
 *
 * Registers the session service and initializes session handling.
 * Provides session management throughout the application.
 */
class SessionServiceProvider extends ServiceProvider
{
    /**
     * Register session service binding
     *
     * Binds the session implementation to the container:
     * - Makes service available via 'session' alias
     * - Initializes with session configuration
     */
    public function register()
    {
        $this->app->bind('session', function ($app) {
            return new Session($app->getConfig('session'));
        });
    }

    /**
     * Bootstrap session handling
     *
     * Starts session if not already active:
     * - Ensures session is available for entire request
     * - Only activates if session isn't already started
     */
    public function boot()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}
