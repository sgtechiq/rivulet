<?php

namespace Rivulet\Cookies;

class Cookies {
    protected $config;

    public function __construct($config) {
        $this->config = $config;
    }

    public function set($key, $value, $expiry = null, $path = null, $domain = null, $secure = null, $httponly = null, $samesite = null) {
        $key = $this->config['prefix'] . $key;
        $expiry = $expiry ?? $this->config['expiry'];
        $path = $path ?? $this->config['path'];
        $domain = $domain ?? $this->config['domain'];
        $secure = $secure ?? $this->config['secure'];
        $httponly = $httponly ?? $this->config['httponly'];
        $samesite = $samesite ?? $this->config['samesite'];

        if ($expiry > 0) {
            $expiry = time() + $expiry;
        }

        setcookie($key, $value, [
            'expires' => $expiry,
            'path' => $path,
            'domain' => $domain,
            'secure' => $secure,
            'httponly' => $httponly,
            'samesite' => $samesite,
        ]);
    }

    public function get($key, $default = null) {
        $key = $this->config['prefix'] . $key;
        return $_COOKIE[$key] ?? $default;
    }

    public function forget($key, $path = null, $domain = null) {
        $this->set($key, '', -3600, $path, $domain);
    }
}