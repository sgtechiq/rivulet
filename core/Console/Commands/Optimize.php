<?php
namespace Rivulet\Console\Commands;

use Rivulet\Rivulet;

class Optimize
{
    /**
     * Rivulet application instance
     * @var Rivulet
     */
    protected $app;

    /**
     * Create new command instance
     * @param Rivulet $app Application instance
     */
    public function __construct(Rivulet $app)
    {
        $this->app = $app;
    }

    /**
     * Execute optimization command
     * Clears cache, logs and routes cache
     * @param array $args Command arguments
     */
    public function execute($args)
    {
        (new CacheClear($this->app))->execute([]);
        (new LogsClear($this->app))->execute([]);
        (new RoutesClear($this->app))->execute([]);
        echo "Optimized\n";
    }
}
