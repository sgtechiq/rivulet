<?php
namespace Rivulet\Providers;

use Rivulet\Database\Connection;

/**
 * Database Service Provider
 *
 * Registers the database connection resolver with the application container.
 * Allows resolving database connections by name from the container.
 */
class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * Register database connection resolver
     *
     * Binds a closure that resolves database connections by name:
     * - Default connection is resolved when no name specified
     * - Additional connections can be resolved by passing connection name
     */
    public function register(): void
    {
        $this->app->bind('db.connection', function ($app, array $parameters = []) {
            $name = $parameters['name'] ?? 'default';
            return Connection::get($name);
        });
    }
}
