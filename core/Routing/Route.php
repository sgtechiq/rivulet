<?php

namespace Rivulet\Routing;

class Route {
    public $method;
    public $uri;
    public $action;
    public $middleware = [];
    public $pattern;

    public function __construct($method, $uri, $action, array $middleware = []) {
        $this->method = strtoupper($method);
        $this->uri = $uri;
        $this->action = $action;
        $this->middleware = $middleware;
    }
}