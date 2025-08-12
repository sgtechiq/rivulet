<?php
namespace Rivulet\Queue;

use Exception;
use Rivulet\Rivulet;

class Queue
{
    protected $app;
    protected $connection;

    public function __construct(Rivulet $app)
    {
        $this->app        = $app;
        $this->connection = $app->getConfig('queue.default');
    }

    public function push($job, $data = [], $queue = 'default')
    {
        $payload = serialize(['job' => $job, 'data' => $data]);
        $driver  = $this->getDriver();
        $driver->push($queue, $payload);
    }

    public function process($queue = 'default', $maxJobs = 0)
    {
        // Process queue worker (infinite if maxJobs=0, finite otherwise to avoid warnings)
        $driver    = $this->getDriver();
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

    protected function runJob($jobData)
    {
        $unserialized = unserialize($jobData['payload']);
        $job          = new $unserialized['job']($unserialized['data']);
        try {
            $job->handle();
                                                        // Delete job on success
            $this->getDriver()->delete($jobData['id']); // Add delete method to drivers
        } catch (\Exception $e) {
            $attempts   = $jobData['attempts'] + 1;
            $maxRetries = $this->app->getConfig("queue.connections.{$this->connection}.max_retries", 3);
            if ($attempts > $maxRetries) {
                                                                      // Move to failed
                $this->getDriver()->fail($jobData, $e->getMessage()); // Add fail method
            } else {
                // Retry with delay
                $delayBase = $this->app->getConfig("queue.connections.{$this->connection}.retry_delay_base", 2);
                $delay     = pow($delayBase, $attempts);
                $this->getDriver()->retry($jobData, $attempts, time() + $delay); // Add retry method
            }
        }
    }

    protected function getDriver()
    {
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
