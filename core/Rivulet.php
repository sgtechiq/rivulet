<?php

namespace Rivulet;

use Dotenv\Dotenv;

class Rivulet {
    private static $instance = null;
    protected $basePath;
    protected $config = [];
    protected $providers = [];
    protected $booted = false;

    // Add to class properties
protected $bindings = [];



// Remove env method (moved to helpers)
    private function __construct() {
        $this->basePath = dirname(__DIR__);
    }
public function bind($abstract, $concrete) {
    $this->bindings[$abstract] = $concrete;
}

public function make($abstract) {
    if (!isset($this->bindings[$abstract])) {
        throw new \Exception("Binding not found for {$abstract}");
    }
    $concrete = $this->bindings[$abstract];
    if ($concrete instanceof \Closure) {
        return $concrete($this);
    }
    return new $concrete($this);
}

// Update bootstrap() after registerProviders()
protected function registerProviders() {
    $providers = $this->config['app']['providers'] ?? [];
    foreach ($providers as $provider) {
        $instance = new $provider($this);
        $instance->register();
        $this->providers[] = $instance;
    }
}

// Add after registerProviders in bootstrap
protected function bootProviders() {
    foreach ($this->providers as $provider) {
        if (method_exists($provider, 'boot')) {
            $provider->boot();
        }
    }
}

// Update bootstrap to call bootProviders after registerProviders
public function bootstrap() {
    if ($this->booted) {
        return;
    }
    $this->loadConfigs();
    $this->registerProviders();
    $this->bootProviders();
    $this->booted = true;
    if (env('APP_DEBUG', true)) {
    set_error_handler(function ($severity, $message, $file, $line) {
        log_message("Error [{$severity}] {$message} in {$file}:{$line}", 'error');
    });
    set_exception_handler(function ($exception) {
        log_message("Exception: {$exception->getMessage()} in {$exception->getFile()}:{$exception->getLine()} \nTrace: {$exception->getTraceAsString()}", 'error');
    });
}
}
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }


   public function loadConfigs() {
    $cacheFile = $this->basePath('storage/cache/config.cache');
    if (file_exists($cacheFile)) {
        $this->config = unserialize(file_get_contents($cacheFile));
        return;
    }

    $configPath = $this->basePath . '/config';
    $files = glob($configPath . '/*.php');
    foreach ($files as $file) {
        $key = basename($file, '.php');
        $this->config[$key] = require $file;
    }

    file_put_contents($cacheFile, serialize($this->config));
}

    public function getConfig($key, $default = null) {
        $segments = explode('.', $key);
        $config = $this->config;
        foreach ($segments as $segment) {
            if (isset($config[$segment])) {
                $config = $config[$segment];
            } else {
                return $default;
            }
        }
        return $config;
    }

    public function basePath($path = '') {
        return $this->basePath . ($path ? '/' . $path : '');
    }

    // Helper to get env vars (global helper will be added later)
    public function env($key, $default = null) {
        return $_ENV[$key] ?? $default;
    }
    
}