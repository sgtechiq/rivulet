<?php
namespace Rivulet\Providers;

use Rivulet\Http\Client;

/**
 * HTTP Client Service Provider
 *
 * Registers the HTTP client service with the application container.
 * Provides a centralized way to make HTTP requests throughout the application.
 */
class HttpClientServiceProvider extends ServiceProvider
{
    /**
     * Register the HTTP client service
     *
     * Binds the HTTP client implementation to the container as a new instance each time.
     * Available via the 'http' alias and through dependency injection.
     */
    public function register(): void
    {
        $this->app->bind('http', function ($app) {
            return new Client();
        });
    }
}
