<?php

namespace Rivulet\Queue\Drivers;

use Predis\Client;

class RedisQueue {
    protected $config;
    protected $redis;

    public function __construct($config) {
        $this->config = $config;
        $this->redis = new Client(); // Assume default redis config
    }

    public function push($queue, $payload) {
        $this->redis->rpush("queues:{$queue}", $payload);
    }

    public function pop($queue) {
        $payload = $this->redis->lpop("queues:{$queue}");
        if ($payload) {
            return ['payload' => $payload];
        }
        return null;
    }
}