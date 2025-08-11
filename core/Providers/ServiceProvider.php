<?php
namespace Rivulet\Providers;

use Rivulet\Rivulet;

/**
 * Base Service Provider
 *
 * Abstract class that defines the service provider contract.
 * All application service providers should extend this class.
 */
abstract class ServiceProvider
{
    /**
     * @var Rivulet Application instance
     */
    protected $app;

    /**
     * Create new service provider instance
     *
     * @param Rivulet $app Application instance
     */
    public function __construct(Rivulet $app)
    {
        $this->app = $app;
    }

    /**
     * Register bindings to the container
     *
     * All service providers must implement this method
     */
    abstract public function register();

    /**
     * Bootstrap any application services
     *
     * Optional method that runs after all providers are registered
     */
    public function boot()
    {
        // Default empty implementation
    }
}
