<?php

namespace Rivulet\Http;

class Response {
    protected $content;
    protected $status;
    protected $headers = [];

    public function __construct($content = '', $status = 200, $headers = []) {
        $this->content = $content;
        $this->status = $status;
        $this->headers = $headers;
    }

    public static function json($data, $status = 200, $headers = []) {
        $headers['Content-Type'] = 'application/json';
        return new self(json_encode($data), $status, $headers);
    }

    public static function text($text, $status = 200, $headers = []) {
        $headers['Content-Type'] = 'text/plain';
        return new self($text, $status, $headers);
    }

    public static function html($html, $status = 200, $headers = []) {
        $headers['Content-Type'] = 'text/html';
        return new self($html, $status, $headers);
    }

    public static function redirect($url, $status = 302) {
        $response = new self('', $status);
        $response->header('Location', $url);
        return $response;
    }

    public function header($key, $value) {
        $this->headers[$key] = $value;
        return $this;
    }

    public function send() {
        http_response_code($this->status);
        foreach ($this->headers as $key => $value) {
            header("$key: $value");
        }
        echo $this->content;
        exit;
    }
    public static function view($template, $data = [], $status = 200) {
    $view = app()->make('view');
    $html = $view->render($template, $data);
    return self::html($html, $status);
}
}