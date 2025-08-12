<?php
namespace Rivulet\Console\Commands;

use Rivulet\Rivulet;

/**
 * Command to clear cached configuration files
 *
 * Removes the compiled configuration cache file, forcing the application
 * to rebuild configuration on next request. This should be run after
 * making changes to configuration files in development.
 */
class ConfigClear
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
     * Executes the config clear command
     *
     * Locates and removes the configuration cache file if it exists.
     * Provides feedback about the operation's success.
     *
     * @param array $args Command arguments (not used in this command)
     * @return void
     */
    public function execute(array $args = [])
    {
        $cacheFile = $this->app->basePath('storage/cache/config.cache');

        if (file_exists($cacheFile)) {
            unlink($cacheFile);
            echo "Config cache cleared\n";
        } else {
            echo "No config cache to clear\n";
        }
    }
}
