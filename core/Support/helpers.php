<?php

if (!function_exists('app')) {
    function app() {
        return \Rivulet\Rivulet::getInstance();
    }
}

if (!function_exists('env')) {
    function env($key, $default = null) {
        return $_ENV[$key] ?? $default;
    }
}

if (!function_exists('config')) {
    function config($key, $default = null) {
        return app()->getConfig($key, $default);
    }
}

if (!function_exists('route')) {
    function route($method, $uri, $controllerOrAction, $methodName = null) {
        app()->make('router')->addRoute($method, $uri, $controllerOrAction, $methodName);
    }
}

if (!function_exists('prefix')) {
    function prefix($prefix, \Closure $callback) {
        app()->make('router')->prefix($prefix, $callback);
    }
}

if (!function_exists('group')) {
    function group(...$args) {
        app()->make('router')->group(...$args);
    }
}

if (!function_exists('middleware')) {
    function middleware($middleware, \Closure $callback) {
        app()->make('router')->middleware($middleware, $callback);
    }
}

if (!function_exists('file_route')) { // Renamed to avoid conflict if 'file' is used
    function file_route($uri, $path) {
        app()->make('router')->addFile($uri, $path);
    }
}

if (!function_exists('json_success')) {
    function json_success($data, $status = 200) {
        return \Rivulet\Http\Response::json(['success' => true, 'data' => $data], $status);
    }
}

if (!function_exists('json_error')) {
    function json_error($message, $status = 400) {
        return \Rivulet\Http\Response::json(['success' => false, 'error' => $message], $status);
    }
}
if (!function_exists('encrypt_password')) {
    function encrypt_password($password) {
        return password_hash($password, PASSWORD_BCRYPT);
    }
}

if (!function_exists('verify_password')) {
    function verify_password($password, $hash) {
        return password_verify($password, $hash);
    }
}
if (!function_exists('class_basename')) {
    function class_basename($class) {
        $class = is_object($class) ? get_class($class) : $class;
        return basename(str_replace('\\', '/', $class));
    }
}
if (!function_exists('log_message')) {
    function log_message($message, $level = 'info') {
        $logger = new \Rivulet\Logging\Logs(app()->getConfig('logging'));
        $logger->log($level, $message);
    }
}

if (!function_exists('cache_get')) {
    function cache_get($key, $default = null) {
        $cache = new \Rivulet\Cache\Cache(app()->getConfig('cache'));
        return $cache->get($key, $default);
    }
}

if (!function_exists('cache_put')) {
    function cache_put($key, $value, $ttl = 3600) {
        $cache = new \Rivulet\Cache\Cache(app()->getConfig('cache'));
        $cache->put($key, $value, $ttl);
    }
}

if (!function_exists('validate')) {
    function validate(array $data, array $rules) {
        $validator = new \Rivulet\Validation\Validator();
        $validator->validate($data, $rules);
    }
}
if (!function_exists('event_fire')) {
    function event_fire($event, $data = []) {
        app()->make('event')->fire($event, $data);
    }
}
if (!function_exists('session_set')) {
    function session_set($key, $value) {
        app()->make('session')->set($key, $value);
    }
}

if (!function_exists('session_get')) {
    function session_get($key, $default = null) {
        return app()->make('session')->get($key, $default);
    }
}

if (!function_exists('session_forget')) {
    function session_forget($key) {
        app()->make('session')->forget($key);
    }
}

if (!function_exists('session_flash')) {
    function session_flash($key, $value) {
        app()->make('session')->flash($key, $value);
    }
}

if (!function_exists('session_get_flash')) {
    function session_get_flash($key, $default = null) {
        return app()->make('session')->getFlash($key, $default);
    }
}
if (!function_exists('cookie_set')) {
    function cookie_set($key, $value, $expiry = null) {
        app()->make('cookie')->set($key, $value, $expiry);
    }
}

if (!function_exists('cookie_get')) {
    function cookie_get($key, $default = null) {
        return app()->make('cookie')->get($key, $default);
    }
}

if (!function_exists('cookie_forget')) {
    function cookie_forget($key) {
        app()->make('cookie')->forget($key);
    }
}