<?php
namespace Rivulet\Session;

class Session
{
    protected $config;

    /**
     * Initialize session with configuration
     * @param array $config Session configuration
     */
    public function __construct($config)
    {
        $this->config = $config;
        // Configure session params
        ini_set('session.gc_maxlifetime', $this->config['lifetime'] * 60);
        session_set_cookie_params([
            'lifetime' => $this->config['lifetime'] * 60,
            'path'     => '/',
            'secure'   => $this->config['secure'],
            'httponly' => $this->config['http_only'],
        ]);
    }

    /**
     * Set session value
     * @param string $key Session key
     * @param mixed $value Value to store
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Get session value
     * @param string $key Session key
     * @param mixed $default Default value if not found
     */
    public function get($key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Remove session value
     * @param string $key Session key
     */
    public function forget($key)
    {
        unset($_SESSION[$key]);
    }

    /**
     * Set flash data (one-time use)
     * @param string $key Flash key
     * @param mixed $value Value to store
     */
    public function flash($key, $value)
    {
        $this->set("flash.{$key}", $value);
    }

    /**
     * Get and remove flash data
     * @param string $key Flash key
     * @param mixed $default Default value if not found
     */
    public function getFlash($key, $default = null)
    {
        $value = $this->get("flash.{$key}", $default);
        $this->forget("flash.{$key}");
        return $value;
    }

    /**
     * Regenerate session ID
     */
    public function regenerate()
    {
        session_regenerate_id(true);
    }

    /**
     * Destroy session
     */
    public function destroy()
    {
        session_destroy();
    }
}
