<?php
namespace Rivulet\Console\Commands;

use Rivulet\Cache\Cache;
use Rivulet\Rivulet;

/**
 * Command to clear the application cache
 *
 * Handles flushing of all cached items from the cache storage
 */
class CacheClear
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
     * Executes the cache clear command
     *
     * Initializes the cache system and clears all cached items
     *
     * @param array $args Command line arguments (not used in this command)
     * @return void
     */
    public function execute(array $args = [])
    {
        $cache = new Cache($this->app->getConfig('cache'));
        $cache->clear();

        echo "Cache cleared\n";
    }
}
