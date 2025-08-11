<?php

namespace Rivulet\Session;

use Exception;

class Session {
    protected $config;

    public function __construct($config) {
        $this->config = $config;
        // Configure session params
        ini_set('session.gc_maxlifetime', $this->config['lifetime'] * 60);
        session_set_cookie_params([
            'lifetime' => $this->config['lifetime'] * 60,
            'path' => '/',
            'secure' => $this->config['secure'],
            'httponly' => $this->config['http_only'],
        ]);
    }

    public function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public function get($key, $default = null) {
        return $_SESSION[$key] ?? $default;
    }

    public function forget($key) {
        unset($_SESSION[$key]);
    }

    public function flash($key, $value) {
        $this->set("flash.{$key}", $value);
    }

    public function getFlash($key, $default = null) {
        $value = $this->get("flash.{$key}", $default);
        $this->forget("flash.{$key}");
        return $value;
    }

    public function regenerate() {
        session_regenerate_id(true);
    }

    public function destroy() {
        session_destroy();
    }
}