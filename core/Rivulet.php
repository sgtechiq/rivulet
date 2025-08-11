<?php
namespace Rivulet;

class Rivulet
{
    private static $instance = null;
    protected $basePath;
    protected $config    = [];
    protected $providers = [];
    protected $booted    = false;
    protected $bindings  = [];

    /**
     * Private constructor for singleton pattern
     */
    private function __construct()
    {
        $this->basePath = dirname(__DIR__);
    }

    /**
     * Get the singleton instance
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Bind a concrete implementation to an abstract
     * @param string $abstract The abstract identifier
     * @param mixed $concrete The concrete implementation
     */
    public function bind($abstract, $concrete)
    {
        $this->bindings[$abstract] = $concrete;
    }

    /**
     * Resolve a binding from the container
     * @param string $abstract The abstract identifier
     * @throws \Exception If binding not found
     */
    public function make($abstract)
    {
        if (! isset($this->bindings[$abstract])) {
            throw new \Exception("Binding not found for {$abstract}");
        }
        $concrete = $this->bindings[$abstract];
        if ($concrete instanceof \Closure) {
            return $concrete($this);
        }
        return new $concrete($this);
    }

    /**
     * Bootstrap the application
     * Loads config, registers providers, and boots them
     */
    public function bootstrap()
    {
        if ($this->booted) {
            return;
        }
        $this->loadConfigs();
        $this->registerProviders();
        $this->bootProviders();
        $this->booted = true;
        if (env('APP_DEBUG', true)) {
            set_error_handler(function ($severity, $message, $file, $line) {
                LogMessage("Error [{$severity}] {$message} in {$file}:{$line}", 'error');
            });
            set_exception_handler(function ($exception) {
                LogMessage("Exception: {$exception->getMessage()} in {$exception->getFile()}:{$exception->getLine()} \nTrace: {$exception->getTraceAsString()}", 'error');
            });
        }
    }

    /**
     * Load configuration files
     */
    public function loadConfigs()
    {
        $cacheFile = $this->basePath('storage/cache/config.cache');
        if (file_exists($cacheFile)) {
            $this->config = unserialize(file_get_contents($cacheFile));
            return;
        }

        $configPath = $this->basePath . '/config';
        $files      = glob($configPath . '/*.php');
        foreach ($files as $file) {
            $key                = basename($file, '.php');
            $this->config[$key] = require $file;
        }

        file_put_contents($cacheFile, serialize($this->config));
    }

    /**
     * Register all service providers
     */
    protected function registerProviders()
    {
        $providers = $this->config['app']['providers'] ?? [];
        foreach ($providers as $provider) {
            $instance = new $provider($this);
            $instance->register();
            $this->providers[] = $instance;
        }
    }

    /**
     * Boot all registered providers
     */
    protected function bootProviders()
    {
        foreach ($this->providers as $provider) {
            if (method_exists($provider, 'boot')) {
                $provider->boot();
            }
        }
    }

    /**
     * Get configuration value by key
     * @param string $key Dot notation config key
     * @param mixed $default Default value if not found
     */
    public function getConfig($key, $default = null)
    {
        $segments = explode('.', $key);
        $config   = $this->config;
        foreach ($segments as $segment) {
            if (isset($config[$segment])) {
                $config = $config[$segment];
            } else {
                return $default;
            }
        }
        return $config;
    }

    /**
     * Get base path with optional suffix
     * @param string $path Optional path suffix
     */
    public function basePath($path = '')
    {
        return $this->basePath . ($path ? '/' . $path : '');
    }

    /**
     * Get environment variable
     * @param string $key Environment key
     * @param mixed $default Default value if not found
     */
    public function env($key, $default = null)
    {
        return $_ENV[$key] ?? $default;
    }
}
