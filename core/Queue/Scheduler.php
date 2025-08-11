<?php

namespace Rivulet\Queue;

use Rivulet\Rivulet;

class Scheduler {
    protected $app;

    public function __construct(Rivulet $app) {
        $this->app = $app;
    }

    public function run() {
        $schedules = $this->app->getConfig('schedule', []);
        $queue = $this->app->make('queue');
        $now = time();
        // Simple everyMinute, everyHour, etc.
        foreach ($schedules as $cron => $task) {
            if ($this->shouldRun($cron, $now)) {
                $queue->push($task['job'], $task['data'] ?? []);
            }
        }
    }

    protected function shouldRun($cron, $now) {
        // Simple mapping, extend for full cron
        if ($cron === 'everyMinute') {
            return true; // Assume called every minute via cron
        }
        // Add more
        return false;
    }
}