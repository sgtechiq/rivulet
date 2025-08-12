<?php
namespace Rivulet\Console\Commands;

use Rivulet\Queue\Scheduler;
use Rivulet\Rivulet;

class ScheduleRun
{
    /**
     * Rivulet application instance
     * @var Rivulet
     */
    protected $app;

    /**
     * Create new scheduler command instance
     * @param Rivulet $app Application instance
     */
    public function __construct(Rivulet $app)
    {
        $this->app = $app;
    }

    /**
     * Execute scheduled jobs
     * Runs all due scheduled jobs through the queue system
     * @param array $args Command arguments (unused)
     */
    public function execute($args)
    {
        $scheduler = new Scheduler($this->app);
        $scheduler->run();
        echo "Scheduled jobs processed\n";
    }
}
