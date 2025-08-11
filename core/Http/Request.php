<?php

namespace Rivulet\Http;

class Request {
    public $method;
    public $uri;
    public $query;
    public $request;
    public $files;
    public $headers;
    public $server;
    public $json;
    public $user = null;
    public static function capture() {
        $request = new self();

        $request->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $request->uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $request->query = $_GET;
        $request->request = $_POST;
        $request->files = $_FILES;
        $request->headers = getallheaders();
        $request->server = $_SERVER;

        // Handle JSON input for API
        $input = file_get_contents('php://input');
        if (!empty($input) && isset($request->headers['Content-Type']) && strpos($request->headers['Content-Type'], 'application/json') !== false) {
            $request->json = json_decode($input, true);
            $request->request = array_merge($request->request, $request->json ?? []);
        }

        return $request;
    }

    public function input($key = null, $default = null) {
        if ($key === null) {
            return $this->request;
        }
        return $this->request[$key] ?? $default;
    }

    public function query($key = null, $default = null) {
        if ($key === null) {
            return $this->query;
        }
        return $this->query[$key] ?? $default;
    }

    public function file($key) {
        return $this->files[$key] ?? null;
    }

    public function header($key, $default = null) {
        return $this->headers[$key] ?? $default;
    }

    public function isJson() {
        return isset($this->headers['Content-Type']) && strpos($this->headers['Content-Type'], 'application/json') !== false;
    }
}