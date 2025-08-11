<?php
namespace Rivulet\Routing;

/**
 * Route Definition
 *
 * Represents a single route with its properties and handlers
 */
class Route
{
    /** @var string HTTP method (GET, POST, etc.) */
    public $method;

    /** @var string URI pattern */
    public $uri;

    /** @var mixed Callable action or controller reference */
    public $action;

    /** @var array Middleware stack */
    public $middleware = [];

    /** @var string Compiled regex pattern */
    public $pattern;

    /**
     * Create new route instance
     * @param string $method HTTP method
     * @param string $uri URI pattern
     * @param mixed $action Route handler
     * @param array $middleware Middleware stack
     */
    public function __construct($method, $uri, $action, array $middleware = [])
    {
        $this->method     = strtoupper($method);
        $this->uri        = $uri;
        $this->action     = $action;
        $this->middleware = $middleware;
    }
}
