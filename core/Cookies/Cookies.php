<?php
namespace Rivulet\Cookies;

class Cookies
{
    /**
     * @var array Cookie configuration settings
     */
    protected $config;

    /**
     * Initialize cookie handler with configuration
     *
     * @param array $config {
     *     @var string $prefix    Cookie name prefix
     *     @var int    $expiry    Default expiry in seconds (0 = session)
     *     @var string $path      Default path
     *     @var string $domain    Default domain
     *     @var bool   $secure    HTTPS-only flag
     *     @var bool   $httponly  HTTP-only access flag
     *     @var string $samesite  SameSite policy
     * }
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Get a cookie value
     *
     * @param string $key     Cookie name (without prefix)
     * @param mixed  $default Default value if cookie doesn't exist
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        $key = $this->config['prefix'] . $key;
        return $_COOKIE[$key] ?? $default;
    }

    /**
     * Set a cookie
     *
     * @param string $key      Cookie name
     * @param mixed  $value    Cookie value
     * @param int    $expiry   Expiry in seconds (null = use config)
     * @param string $path     Path (null = use config)
     * @param string $domain   Domain (null = use config)
     * @param bool   $secure   HTTPS-only (null = use config)
     * @param bool   $httponly HTTP-only (null = use config)
     * @param string $samesite SameSite policy (null = use config)
     */
    public function set(
        string $key,
        $value,
        ?int $expiry = null,
        ?string $path = null,
        ?string $domain = null,
        ?bool $secure = null,
        ?bool $httponly = null,
        ?string $samesite = null
    ) {
        $key     = $this->config['prefix'] . $key;
        $options = [
            'expires'  => ($expiry ?? $this->config['expiry']) > 0
            ? time() + ($expiry ?? $this->config['expiry'])
            : 0,
            'path'     => $path ?? $this->config['path'],
            'domain'   => $domain ?? $this->config['domain'],
            'secure'   => $secure ?? $this->config['secure'],
            'httponly' => $httponly ?? $this->config['httponly'],
            'samesite' => $samesite ?? $this->config['samesite'],
        ];

        setcookie($key, $value, $options);
    }

    /**
     * Delete a cookie
     *
     * @param string $key    Cookie name
     * @param string $path   Path (null = use config)
     * @param string $domain Domain (null = use config)
     */
    public function forget(string $key, ?string $path = null, ?string $domain = null)
    {
        $this->set($key, '', -3600, $path, $domain);
    }
}
