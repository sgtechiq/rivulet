<?php

namespace Rivulet\Queue;

use Rivulet\Rivulet;
use Exception;

class Queue {
    protected $app;
    protected $connection;

    public function __construct(Rivulet $app) {
        $this->app = $app;
        $this->connection = $app->getConfig('queue.default');
    }

    public function push($job, $data = [], $queue = 'default') {
        $payload = serialize(['job' => $job, 'data' => $data]);
        $driver = $this->getDriver();
        $driver->push($queue, $payload);
    }

    public function process($queue = 'default', $maxJobs = 0) {
        // Process queue worker (infinite if maxJobs=0, finite otherwise to avoid warnings)
        $driver = $this->getDriver();
        $processed = 0;
        while (true) {
            $job = $driver->pop($queue);
            if ($job) {
                $this->runJob($job);
                $processed++;
                if ($maxJobs > 0 && $processed >= $maxJobs) {
                    break;
                }
            } else {
                // No job, sleep to avoid CPU spin
                sleep(1);
            }
        }
    }

    protected function runJob($jobData) {
        $unserialized = unserialize($jobData['payload']);
        $job = new $unserialized['job']($unserialized['data']);
        $job->handle();
        // Handle failures, retries
    }

    protected function getDriver() {
        $config = $this->app->getConfig("queue.connections.{$this->connection}");
        switch ($config['driver']) {
            case 'database':
                return new Drivers\DatabaseQueue($config);
            case 'redis':
                return new Drivers\RedisQueue($config);
            default:
                throw new Exception("Unsupported queue driver");
        }
    }
}