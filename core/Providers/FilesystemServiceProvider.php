<?php
namespace Rivulet\Providers;

use Rivulet\Filesystem\Filesystem;

/**
 * Filesystem Service Provider
 *
 * Registers the filesystem service with the application container.
 * Provides centralized access to file operations throughout the application.
 */
class FilesystemServiceProvider extends ServiceProvider
{
    /**
     * Register the filesystem service
     *
     * Binds a Filesystem instance to the container, making it available:
     * - Via direct container access ('filesystem')
     * - Through dependency injection
     */
    public function register(): void
    {
        $this->app->bind('filesystem', function ($app) {
            return new Filesystem($app);
        });
    }
}
