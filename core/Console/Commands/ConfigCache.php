<?php
namespace Rivulet\Console\Commands;

use Rivulet\Rivulet;

/**
 * Command to cache application configuration
 *
 * Compiles and caches all configuration files for faster application loading.
 * This should be run after any configuration changes in production environments.
 */
class ConfigCache
{
    /**
     * @var Rivulet The application instance
     */
    protected $app;

    /**
     * Constructor
     *
     * @param Rivulet $app The application instance
     */
    public function __construct(Rivulet $app)
    {
        $this->app = $app;
    }

    /**
     * Executes the config cache command
     *
     * Forces loading and caching of all configuration files.
     *
     * @param array $args Command arguments (not used in this command)
     * @return void
     */
    public function execute(array $args = [])
    {
        $this->app->loadConfigs(); // Forces caching
        echo "Configs cached\n";
    }
}
