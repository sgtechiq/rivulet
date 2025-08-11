<?php
namespace Rivulet\Http;

/**
 * HTTP Response Handler
 *
 * Provides various methods to generate and send HTTP responses including:
 * - JSON responses
 * - Text/HTML content
 * - Redirects
 * - Custom headers
 */
class Response
{
    /** @var mixed Response content */
    protected $content;

    /** @var int HTTP status code */
    protected $status;

    /** @var array HTTP headers */
    protected $headers = [];

    /**
     * Create new response
     *
     * @param mixed $content Response body
     * @param int $status HTTP status code
     * @param array $headers HTTP headers
     */
    public function __construct($content = '', int $status = 200, array $headers = [])
    {
        $this->content = $content;
        $this->status  = $status;
        $this->headers = $headers;
    }

    // Factory Methods --------------------------------------------------------

    /**
     * Create JSON response
     *
     * @param mixed $data Data to encode
     * @param int $status HTTP status code
     * @param array $headers Additional headers
     * @return self
     */
    public static function json($data, int $status = 200, array $headers = []): self
    {
        $headers['Content-Type'] = 'application/json';
        return new self(json_encode($data), $status, $headers);
    }

    /**
     * Create plain text response
     *
     * @param string $text Response text
     * @param int $status HTTP status code
     * @param array $headers Additional headers
     * @return self
     */
    public static function text(string $text, int $status = 200, array $headers = []): self
    {
        $headers['Content-Type'] = 'text/plain';
        return new self($text, $status, $headers);
    }

    /**
     * Create HTML response
     *
     * @param string $html HTML content
     * @param int $status HTTP status code
     * @param array $headers Additional headers
     * @return self
     */
    public static function html(string $html, int $status = 200, array $headers = []): self
    {
        $headers['Content-Type'] = 'text/html';
        return new self($html, $status, $headers);
    }

    /**
     * Create redirect response
     *
     * @param string $url Target URL
     * @param int $status Redirect status code (default: 302)
     * @return self
     */
    public static function redirect(string $url, int $status = 302): self
    {
        return (new self('', $status))->header('Location', $url);
    }

    /**
     * Create view response
     *
     * @param string $template Template name
     * @param array $data View data
     * @param int $status HTTP status code
     * @return self
     */
    public static function view(string $template, array $data = [], int $status = 200): self
    {
        $view = app()->make('view');
        return self::html($view->render($template, $data), $status);
    }

    // Builder Methods -------------------------------------------------------

    /**
     * Add/set response header
     *
     * @param string $key Header name
     * @param string $value Header value
     * @return self
     */
    public function header(string $key, string $value): self
    {
        $this->headers[$key] = $value;
        return $this;
    }

    // Output Methods --------------------------------------------------------

    /**
     * Send response to client
     */
    public function send(): void
    {
        http_response_code($this->status);

        foreach ($this->headers as $key => $value) {
            header("$key: $value");
        }

        echo $this->content;
        exit;
    }
}
