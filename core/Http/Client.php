<?php
namespace Rivulet\Http;

use Exception;

/**
 * HTTP Client
 *
 * Provides fluent interface for making HTTP requests with:
 * - GET, POST, PUT, DELETE methods
 * - JSON and form data support
 * - Basic and Bearer authentication
 * - Custom headers
 */
class Client
{
    /**
     * @var string Request URL
     */
    protected $url;

    /**
     * @var string HTTP method (GET, POST, etc.)
     */
    protected $method = 'GET';

    /**
     * @var array Request headers
     */
    protected $headers = [];

    /**
     * @var mixed Request body
     */
    protected $body;

    /**
     * @var string|null Authentication header
     */
    protected $auth;

    // HTTP Method Shortcuts ---------------------------------------------------

    /**
     * Prepare GET request
     *
     * @param string $url Target URL
     * @return self
     */
    public function get(string $url): self
    {
        $this->method = 'GET';
        $this->url    = $url;
        return $this;
    }

    /**
     * Prepare POST request
     *
     * @param string $url Target URL
     * @return self
     */
    public function post(string $url): self
    {
        $this->method = 'POST';
        $this->url    = $url;
        return $this;
    }

    /**
     * Prepare PUT request
     *
     * @param string $url Target URL
     * @return self
     */
    public function put(string $url): self
    {
        $this->method = 'PUT';
        $this->url    = $url;
        return $this;
    }

    /**
     * Prepare DELETE request
     *
     * @param string $url Target URL
     * @return self
     */
    public function delete(string $url): self
    {
        $this->method = 'DELETE';
        $this->url    = $url;
        return $this;
    }

    // Request Configuration ---------------------------------------------------

    /**
     * Set request headers
     *
     * @param array $headers Associative array of headers
     * @return self
     */
    public function headers(array $headers): self
    {
        $this->headers = array_merge($this->headers, $headers);
        return $this;
    }

    /**
     * Set raw request body
     *
     * @param mixed $body Request body content
     * @return self
     */
    public function body($body): self
    {
        $this->body = $body;
        return $this;
    }

    /**
     * Set JSON request body
     *
     * @param mixed $body Data to be JSON encoded
     * @return self
     */
    public function json($body): self
    {
        $this->body                    = json_encode($body);
        $this->headers['Content-Type'] = 'application/json';
        return $this;
    }

    /**
     * Set form data request body
     *
     * @param array $form Form data
     * @return self
     */
    public function form(array $form): self
    {
        $this->body                    = http_build_query($form);
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        return $this;
    }

    // Authentication ---------------------------------------------------------

    /**
     * Set Basic Authentication
     *
     * @param string $user Username
     * @param string $pass Password
     * @return self
     */
    public function basicAuth(string $user, string $pass): self
    {
        $this->auth = 'Basic ' . base64_encode("{$user}:{$pass}");
        return $this;
    }

    /**
     * Set Bearer Token Authentication
     *
     * @param string $token Access token
     * @return self
     */
    public function bearerAuth(string $token): self
    {
        $this->auth = "Bearer {$token}";
        return $this;
    }

    // Execution --------------------------------------------------------------

    /**
     * Execute the HTTP request
     *
     * @return object Response object with:
     *         - status (int) HTTP status code
     *         - body (string) Response body
     *         - headers (string) Response headers
     *         - json() method to decode JSON responses
     *         - text() method to get raw response
     * @throws Exception If request fails
     */
    public function send()
    {
        $ch = curl_init($this->url);

        // Set CURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->method);

        if ($this->body) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->body);
        }

        // Prepare headers
        $curlHeaders = [];
        foreach ($this->headers as $key => $value) {
            $curlHeaders[] = "{$key}: {$value}";
        }

        if ($this->auth) {
            $curlHeaders[] = "Authorization: {$this->auth}";
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $curlHeaders);

        // Execute request
        $response = curl_exec($ch);
        $status   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headers  = curl_getinfo($ch, CURLINFO_HEADER_OUT);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new Exception("HTTP request failed: {$error}");
        }

        curl_close($ch);

        return new class($status, $response, $headers)
        {
            public $status;
            public $body;
            public $headers;

            public function __construct($status, $body, $headers)
            {
                $this->status  = $status;
                $this->body    = $body;
                $this->headers = $headers;
            }

            /**
             * Decode JSON response
             * @return mixed
             */
            public function json()
            {
                return json_decode($this->body, true);
            }

            /**
             * Get raw response body
             * @return string
             */
            public function text()
            {
                return $this->body;
            }
        };
    }
}
