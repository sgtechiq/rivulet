<?php

use Carbon\Carbon;
use Rivulet\Cache\Cache;
use Rivulet\Http\Response;
use Rivulet\Logging\Logs;
use Rivulet\Validation\Validator;

// Application Helpers
if (! function_exists('app')) {
    // app
    function app()
    {
        return \Rivulet\Rivulet::getInstance();
    }
}

if (! function_exists('env')) {
    // env
    function env($key, $default = null)
    {
        return $_ENV[$key] ?? $default;
    }
}

if (! function_exists('config')) {
    // config
    function config($key, $default = null)
    {
        return app()->getConfig($key, $default);
    }
}

// Routing Helpers
if (! function_exists('route')) {
    // route
    function route($method, $uri, $controllerOrAction, $methodName = null)
    {
        app()->make('router')->addRoute($method, $uri, $controllerOrAction, $methodName);
    }
}

if (! function_exists('prefix')) {
    // prefix
    function prefix($prefix, Closure $callback)
    {
        app()->make('router')->prefix($prefix, $callback);
    }
}

if (! function_exists('group')) {
    // group
    function group(...$args)
    {
        app()->make('router')->group(...$args);
    }
}

if (! function_exists('middleware')) {
    // middleware
    function middleware($middleware, Closure $callback)
    {
        app()->make('router')->middleware($middleware, $callback);
    }
}

if (! function_exists('endpoint')) {
    // endpoint
    function endpoint($uri, $controller) {
        app()->make('router')->endpoint($uri, $controller);
    }
}

if (! function_exists('fileroute')) {
    // fileroute
    function fileroute($uri, $path)
    {
        app()->make('router')->addFile($uri, $path);
    }
}

// Response Helpers
if (! function_exists('jsonSuccess')) {
    // jsonSuccess
    function jsonSuccess($data, $status = 200)
    {
        return Response::json(['success' => true, 'data' => $data], $status);
    }
}

if (! function_exists('jsonError')) {
    // jsonError
    function jsonError($message, $status = 400)
    {
        return Response::json(['success' => false, 'error' => $message], $status);
    }
}

// Security Helpers
if (! function_exists('PassEncrypt')) {
    // PassEncrypt
    function PassEncrypt($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }
}

if (! function_exists('PassVerify')) {
    // PassVerify
    function PassVerify($password, $hash)
    {
        return password_verify($password, $hash);
    }
}

// Utility Helpers
if (! function_exists('BaseClassName')) {
    // BaseClassName
    function BaseClassName($class)
    {
        $class = is_object($class) ? get_class($class) : $class;
        return basename(str_replace('\\', '/', $class));
    }
}

// Logging Helpers
if (! function_exists('LogMessage')) {
    // LogMessage
    function LogMessage($message, $level = 'info')
    {
        $logger = new Logs(app()->getConfig('logging'));
        $logger->log($level, $message);
    }
}

// Cache Helpers
if (! function_exists('GetCache')) {
    // GetCache
    function GetCache($key, $default = null)
    {
        $cache = new Cache(app()->getConfig('cache'));
        return $cache->get($key, $default);
    }
}

if (! function_exists('PutCache')) {
    // PutCache
    function PutCache($key, $value, $ttl = 3600)
    {
        $cache = new Cache(app()->getConfig('cache'));
        $cache->put($key, $value, $ttl);
    }
}

// Validation Helpers
if (! function_exists('validate')) {
    // validate
    function validate(array $data, array $rules)
    {
        $validator = new Validator();
        $validator->validate($data, $rules);
    }
}

// Event Helpers
if (! function_exists('TriggerEvent')) {
    // TriggerEvent
    function TriggerEvent($event, $data = [])
    {
        app()->make('event')->fire($event, $data);
    }
}

// Session Helpers
if (! function_exists('SetSession')) {
    // SetSession
    function SetSession($key, $value)
    {
        app()->make('session')->set($key, $value);
    }
}

if (! function_exists('GetSession')) {
    // GetSession
    function GetSession($key, $default = null)
    {
        return app()->make('session')->get($key, $default);
    }
}

if (! function_exists('ForgetSession')) {
    // ForgetSession
    function ForgetSession($key)
    {
        app()->make('session')->forget($key);
    }
}

if (! function_exists('FlashSession')) {
    // FlashSession
    function FlashSession($key, $value)
    {
        app()->make('session')->flash($key, $value);
    }
}

if (! function_exists('GetFlashSession')) {
    // GetFlashSession
    function GetFlashSession($key, $default = null)
    {
        return app()->make('session')->getFlash($key, $default);
    }
}

// Cookie Helpers
if (! function_exists('SetCookie')) {
    // SetCookie
    function SetCookie($key, $value, $expiry = null)
    {
        app()->make('cookie')->set($key, $value, $expiry);
    }
}

if (! function_exists('GetCookie')) {
    // GetCookie
    function GetCookie($key, $default = null)
    {
        return app()->make('cookie')->get($key, $default);
    }
}

if (! function_exists('ForgetCookie')) {
    // ForgetCookie
    function ForgetCookie($key)
    {
        app()->make('cookie')->forget($key);
    }
}

if (! function_exists('carbon')) {
    // carbon
    function carbon($time = null)
    {
        return new Carbon($time);
    }
}