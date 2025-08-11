<?php

namespace Rivulet\Cache;

use Exception;

class Cache {
    protected $path;

    public function __construct($config) {
        $this->path = $config['path'] ?? dirname(__DIR__, 2) . '/storage/cache';
        if (!is_dir($this->path)) {
            mkdir($this->path, 0755, true);
        }
    }

    public function get($key, $default = null) {
        $file = $this->getCacheFile($key);
        if (!file_exists($file)) {
            return $default;
        }
        $content = file_get_contents($file);
        [$expiry, $value] = unserialize($content);
        if ($expiry < time()) {
            unlink($file);
            return $default;
        }
        return $value;
    }

    public function put($key, $value, $ttl = 3600) {
        $file = $this->getCacheFile($key);
        $expiry = time() + $ttl;
        $content = serialize([$expiry, $value]);
        file_put_contents($file, $content, LOCK_EX);
    }

    public function clear() {
        $files = glob($this->path . '/*');
        foreach ($files as $file) {
            unlink($file);
        }
    }

    protected function getCacheFile($key) {
        return $this->path . '/' . md5($key) . '.cache';
    }
}