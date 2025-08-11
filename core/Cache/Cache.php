<?php
namespace Rivulet\Cache;

use Exception;

class Cache
{
    /**
     * @var string Path to cache storage directory
     */
    protected $path;

    /**
     * Initialize cache with configuration
     *
     * @param array $config Configuration array
     * @throws Exception If cache directory cannot be created
     */
    public function __construct($config)
    {
        $this->path = $config['path'] ?? dirname(__DIR__, 2) . '/storage/cache';
        if (! is_dir($this->path)) {
            mkdir($this->path, 0755, true);
        }
    }

    /**
     * Store an item in the cache
     *
     * @param string $key Cache key
     * @param mixed $value Value to store
     * @param int $ttl Time-to-live in seconds
     */
    public function put($key, $value, $ttl = 3600)
    {
        $file    = $this->getCacheFile($key);
        $expiry  = time() + $ttl;
        $content = serialize([$expiry, $value]);
        file_put_contents($file, $content, LOCK_EX);
    }

    /**
     * Retrieve an item from cache
     *
     * @param string $key Cache key
     * @param mixed $default Default value if not found
     * @return mixed Cached value or default
     */
    public function get($key, $default = null)
    {
        $file = $this->getCacheFile($key);

        if (! file_exists($file)) {
            return $default;
        }

        $content          = file_get_contents($file);
        [$expiry, $value] = unserialize($content);

        if ($expiry < time()) {
            unlink($file);
            return $default;
        }

        return $value;
    }

    /**
     * Clear all cached items
     */
    public function clear()
    {
        $files = glob($this->path . '/*');

        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    /**
     * Get full path to cache file for given key
     *
     * @param string $key Cache key
     * @return string Full file path
     */
    protected function getCacheFile($key)
    {
        return $this->path . '/' . md5($key) . '.cache';
    }
}{

}
