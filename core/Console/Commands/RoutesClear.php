<?php
namespace Rivulet\Console\Commands;

use Rivulet\Rivulet;

class RoutesClear
{
    /**
     * Rivulet application instance
     * @var Rivulet
     */
    protected $app;

    /**
     * Create new routes clear command instance
     * @param Rivulet $app Application instance
     */
    public function __construct(Rivulet $app)
    {
        $this->app = $app;
    }

    /**
     * Execute routes cache clearing command
     * Removes cached routes file if it exists
     * @param array $args Command arguments (unused)
     */
    public function execute($args)
    {
        $cacheFile = $this->app->basePath('storage/cache/routes.cache');

        if (file_exists($cacheFile)) {
            unlink($cacheFile);
            echo "Routes cache cleared\n";
        } else {
            echo "No routes cache to clear\n";
        }
    }
}
