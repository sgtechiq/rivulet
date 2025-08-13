<?php

use Carbon\Carbon;
use Rivulet\Cache\Cache;
use Rivulet\Http\Response;
use Rivulet\Logging\Logs;
use Rivulet\Validation\Validator;

/**
 * Application Core Helpers
 *
 * Global helper functions for common framework operations
 */

// Application Helpers
if (! function_exists('app')) {
    /**
     * Get the application instance
     */
    function app()
    {
        return \Rivulet\Rivulet::getInstance();
    }
}

if (! function_exists('env')) {
    /**
     * Get environment variable
     * @param string $key Environment key
     * @param mixed $default Default value if not found
     */
    function env($key, $default = null)
    {
        return $_ENV[$key] ?? $default;
    }
}

if (! function_exists('config')) {
    /**
     * Get configuration value
     * @param string $key Config key
     * @param mixed $default Default value if not found
     */
    function config($key, $default = null)
    {
        return app()->getConfig($key, $default);
    }
}

// Routing Helpers
if (! function_exists('route')) {
    /**
     * Register a new route
     * @param string $method HTTP method
     * @param string $uri Route URI
     * @param mixed $controllerOrAction Controller class or closure
     * @param string|null $methodName Controller method name if using class
     */
    function route($method, $uri, $controllerOrAction, $methodName = null)
    {
        app()->make('router')->addRoute($method, $uri, $controllerOrAction, $methodName);
    }
}

if (! function_exists('prefix')) {
    /**
     * Create route group with prefix
     * @param string $prefix URI prefix
     * @param Closure $callback Route definitions
     */
    function prefix($prefix, Closure $callback)
    {
        app()->make('router')->prefix($prefix, $callback);
    }
}

if (! function_exists('group')) {
    /**
     * Create route group
     * @param mixed ...$args Group parameters
     */
    function group(...$args)
    {
        app()->make('router')->group(...$args);
    }
}

if (! function_exists('middleware')) {
    /**
     * Apply middleware to route group
     * @param mixed $middleware Middleware class or array
     * @param Closure $callback Route definitions
     */
    function middleware($middleware, Closure $callback)
    {
        app()->make('router')->middleware($middleware, $callback);
    }
}

if (! function_exists('endpoint')) {
    function endpoint($uri, $controller) {
        app()->make('router')->endpoint($uri, $controller);
    }
}

if (! function_exists('fileroute')) {
    /**
     * Register file route
     * @param string $uri Route URI
     * @param string $path File path
     */
    function fileroute($uri, $path)
    {
        app()->make('router')->addFile($uri, $path);
    }
}

// Response Helpers
if (! function_exists('jsonSuccess')) {
    /**
     * Return JSON success response
     * @param mixed $data Response data
     * @param int $status HTTP status code
     */
    function jsonSuccess($data, $status = 200)
    {
        return Response::json(['success' => true, 'data' => $data], $status);
    }
}

if (! function_exists('jsonError')) {
    /**
     * Return JSON error response
     * @param string $message Error message
     * @param int $status HTTP status code
     */
    function jsonError($message, $status = 400)
    {
        return Response::json(['success' => false, 'error' => $message], $status);
    }
}

// Security Helpers
if (! function_exists('PassEncrypt')) {
    /**
     * Hash password using bcrypt
     * @param string $password Plain text password
     */
    function PassEncrypt($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }
}

if (! function_exists('PassVerify')) {
    /**
     * Verify password against hash
     * @param string $password Plain text password
     * @param string $hash Hashed password
     */
    function PassVerify($password, $hash)
    {
        return password_verify($password, $hash);
    }
}

// Utility Helpers
if (! function_exists('BaseClassName')) {
    /**
     * Get class name without namespace
     * @param mixed $class Class name or object
     */
    function BaseClassName($class)
    {
        $class = is_object($class) ? get_class($class) : $class;
        return basename(str_replace('\\', '/', $class));
    }
}

// Logging Helpers
if (! function_exists('LogMessage')) {
    /**
     * Log message with specified level
     * @param string $message Log message
     * @param string $level Log level
     */
    function LogMessage($message, $level = 'info')
    {
        $logger = new Logs(app()->getConfig('logging'));
        $logger->log($level, $message);
    }
}

// Cache Helpers
if (! function_exists('GetCache')) {
    /**
     * Get cached value
     * @param string $key Cache key
     * @param mixed $default Default value
     */
    function GetCache($key, $default = null)
    {
        $cache = new Cache(app()->getConfig('cache'));
        return $cache->get($key, $default);
    }
}

if (! function_exists('PutCache')) {
    /**
     * Store value in cache
     * @param string $key Cache key
     * @param mixed $value Value to store
     * @param int $ttl Time to live in seconds
     */
    function PutCache($key, $value, $ttl = 3600)
    {
        $cache = new Cache(app()->getConfig('cache'));
        $cache->put($key, $value, $ttl);
    }
}

// Validation Helpers
if (! function_exists('validate')) {
    /**
     * Validate data against rules
     * @param array $data Data to validate
     * @param array $rules Validation rules
     */
    function validate(array $data, array $rules)
    {
        $validator = new Validator();
        $validator->validate($data, $rules);
    }
}

// Event Helpers
if (! function_exists('TriggerEvent')) {
    /**
     * Fire an event
     * @param string $event Event name
     * @param array $data Event data
     */
    function TriggerEvent($event, $data = [])
    {
        app()->make('event')->fire($event, $data);
    }
}

// Session Helpers
if (! function_exists('SetSession')) {
    /**
     * Set session value
     * @param string $key Session key
     * @param mixed $value Value to store
     */
    function SetSession($key, $value)
    {
        app()->make('session')->set($key, $value);
    }
}

if (! function_exists('GetSession')) {
    /**
     * Get session value
     * @param string $key Session key
     * @param mixed $default Default value
     */
    function GetSession($key, $default = null)
    {
        return app()->make('session')->get($key, $default);
    }
}

if (! function_exists('ForgetSession')) {
    /**
     * Remove session value
     * @param string $key Session key
     */
    function ForgetSession($key)
    {
        app()->make('session')->forget($key);
    }
}

if (! function_exists('FlashSession')) {
    /**
     * Set flash session value
     * @param string $key Session key
     * @param mixed $value Value to store
     */
    function FlashSession($key, $value)
    {
        app()->make('session')->flash($key, $value);
    }
}

if (! function_exists('GetFlashSession')) {
    /**
     * Get flash session value
     * @param string $key Session key
     * @param mixed $default Default value
     */
    function GetFlashSession($key, $default = null)
    {
        return app()->make('session')->getFlash($key, $default);
    }
}

// Cookie Helpers
if (! function_exists('SetCookie')) {
    /**
     * Set cookie
     * @param string $key Cookie name
     * @param mixed $value Cookie value
     * @param int|null $expiry Expiration time in seconds
     */
    function SetCookie($key, $value, $expiry = null)
    {
        app()->make('cookie')->set($key, $value, $expiry);
    }
}

if (! function_exists('GetCookie')) {
    /**
     * Get cookie value
     * @param string $key Cookie name
     * @param mixed $default Default value
     */
    function GetCookie($key, $default = null)
    {
        return app()->make('cookie')->get($key, $default);
    }
}

if (! function_exists('ForgetCookie')) {
    /**
     * Remove cookie
     * @param string $key Cookie name
     */
    function ForgetCookie($key)
    {
        app()->make('cookie')->forget($key);
    }
}
if (! function_exists('carbon')) {
    function carbon($time = null)
    {
        return new Carbon($time);
    }
}
