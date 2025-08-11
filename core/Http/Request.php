<?php
namespace Rivulet\Http;

/**
 * HTTP Request Handler
 *
 * Encapsulates and normalizes HTTP request data including:
 * - Request method and URI
 * - Query parameters
 * - Request payload (form/json)
 * - File uploads
 * - Headers
 */
class Request
{
    /** @var string HTTP method (GET, POST, etc.) */
    public $method;

    /** @var string Request URI path */
    public $uri;

    /** @var array Query string parameters */
    public $query = [];

    /** @var array Request payload (POST/json data) */
    public $request = [];

    /** @var array Uploaded files */
    public $files = [];

    /** @var array HTTP headers */
    public $headers = [];

    /** @var array Server parameters */
    public $server = [];

    /** @var array|null Parsed JSON data */
    public $json = null;

    /** @var mixed Authenticated user */
    public $user = null;

    /**
     * Create new request from global variables
     *
     * @return self
     */
    public static function capture(): self
    {
        $request = new self();

        // Capture basic request data
        $request->method  = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $request->uri     = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $request->query   = $_GET;
        $request->request = $_POST;
        $request->files   = $_FILES;
        $request->headers = getallheaders();
        $request->server  = $_SERVER;

        // Parse JSON input if present
        $input = file_get_contents('php://input');
        if ($input && strpos($request->header('Content-Type', ''), 'application/json') !== false) {
            $request->json    = json_decode($input, true) ?? [];
            $request->request = array_merge($request->request, $request->json);
        }

        return $request;
    }

    /**
     * Get request input value(s)
     *
     * @param string|null $key Parameter name (null for all)
     * @param mixed $default Default value if not found
     * @return mixed
     */
    public function input(?string $key = null, $default = null)
    {
        return $key === null ? $this->request : ($this->request[$key] ?? $default);
    }

    /**
     * Get query parameter(s)
     *
     * @param string|null $key Parameter name (null for all)
     * @param mixed $default Default value if not found
     * @return mixed
     */
    public function query(?string $key = null, $default = null)
    {
        return $key === null ? $this->query : ($this->query[$key] ?? $default);
    }

    /**
     * Get uploaded file
     *
     * @param string $key File input name
     * @return array|null File data or null
     */
    public function file(string $key): ?array
    {
        return $this->files[$key] ?? null;
    }

    /**
     * Get header value
     *
     * @param string $key Header name
     * @param mixed $default Default value if not found
     * @return mixed
     */
    public function header(string $key, $default = null)
    {
        return $this->headers[$key] ?? $default;
    }

    /**
     * Check if request contains JSON payload
     *
     * @return bool
     */
    public function isJson(): bool
    {
        return strpos($this->header('Content-Type', ''), 'application/json') !== false;
    }
}
