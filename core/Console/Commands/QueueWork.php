<?php

namespace Rivulet\Console\Commands;

use Rivulet\Rivulet;
use Rivulet\Queue\Queue;

class QueueWork {
    protected $app;

    public function __construct(Rivulet $app) {
        $this->app = $app;
    }

    public function execute($args) {
        $queue = $args[0] ?? 'default';
        $max = (int) ($args[1] ?? 0);
        $q = $this->app->make('queue');
        $q->process($queue, $max);
    }
}