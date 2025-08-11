<?php
namespace Rivulet\Providers;

use Rivulet\Cookies\Cookies;

/**
 * Cookies Service Provider
 *
 * Registers the cookie handling service with the application container
 */
class CookiesServiceProvider extends ServiceProvider
{
    /**
     * Register cookie service binding
     *
     * Binds the Cookies implementation to the container:
     * - Configures cookies with application settings
     * - Makes service available via 'cookie' alias
     */
    public function register(): void
    {
        $this->app->bind('cookie', function ($app) {
            $config = $app->getConfig('cookies') ?? [];
            return new Cookies($config);
        });
    }
}
