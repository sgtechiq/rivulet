<?php

namespace Rivulet\Console\Commands;

use Rivulet\Rivulet;
use Rivulet\Queue\Scheduler;

class ScheduleRun {
    protected $app;

    public function __construct(Rivulet $app) {
        $this->app = $app;
    }

    public function execute($args) {
        $scheduler = new Scheduler($this->app);
        $scheduler->run();
        echo "Schedule run\n";
    }
}