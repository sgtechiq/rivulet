<?php
namespace Rivulet\Console\Commands;

use Rivulet\Queue\Queue;
use Rivulet\Rivulet;

class QueueWork
{
    /**
     * Rivulet application instance
     * @var Rivulet
     */
    protected $app;

    /**
     * Create new queue worker command instance
     * @param Rivulet $app Application instance
     */
    public function __construct(Rivulet $app)
    {
        $this->app = $app;
    }

    /**
     * Execute queue worker command
     * Processes jobs from the specified queue
     * @param array $args Command arguments [queue_name, max_jobs]
     */
    public function execute($args)
    {
        $queue = $args[0] ?? 'default';
        $max   = (int) ($args[1] ?? 0);
        $q     = $this->app->make('queue');
        $q->process($queue, $max);
    }
}
