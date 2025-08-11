<?php
namespace Rivulet\Providers;

use Exception;

/**
 * Application Service Provider
 *
 * Handles:
 * - Auto-registration of service classes
 * - Booting of service classes
 * - Loading of helper functions
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register application services
     */
    public function register(): void
    {
        $this->registerServices('register');
    }

    /**
     * Bootstrap application services
     */
    public function boot(): void
    {
        $this->registerServices('boot');
        $this->loadHelpers();
    }

    /**
     * Register or boot services from app/Services directory
     *
     * @param string $method Either 'register' or 'boot'
     * @throws Exception If service initialization fails
     */
    protected function registerServices(string $method): void
    {
        $servicesDir = $this->app->basePath('app/Services');

        if (! is_dir($servicesDir)) {
            return;
        }

        foreach (glob($servicesDir . '/*.php') as $file) {
            $className = 'App\\Services\\' . basename($file, '.php');

            if (! class_exists($className)) {
                continue;
            }

            try {
                $service = new $className($this->app);
                if (method_exists($service, $method)) {
                    $service->{$method}();
                }
            } catch (Exception $e) {
                throw new Exception("Failed to initialize service {$className}: " . $e->getMessage());
            }
        }
    }

    /**
     * Load helper functions from app/Helpers directory
     */
    protected function loadHelpers(): void
    {
        $helpersDir = $this->app->basePath('app/Helpers');

        if (! is_dir($helpersDir)) {
            return;
        }

        foreach (glob($helpersDir . '/*.php') as $file) {
            require_once $file;
        }
    }
}
