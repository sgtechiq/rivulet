<?php

namespace Rivulet\Http;

use Exception;
use stdClass;

class Client {
    protected $url;
    protected $method = 'GET';
    protected $headers = [];
    protected $body;
    protected $auth;

    public function get($url) {
        $this->method = 'GET';
        $this->url = $url;
        return $this;
    }

    public function post($url) {
        $this->method = 'POST';
        $this->url = $url;
        return $this;
    }

    public function put($url) {
        $this->method = 'PUT';
        $this->url = $url;
        return $this;
    }

    public function delete($url) {
        $this->method = 'DELETE';
        $this->url = $url;
        return $this;
    }

    public function headers(array $headers) {
        $this->headers = array_merge($this->headers, $headers);
        return $this;
    }

    public function body($body) {
        $this->body = $body;
        return $this;
    }

    public function json($body) {
        $this->body = json_encode($body);
        $this->headers['Content-Type'] = 'application/json';
        return $this;
    }

    public function form(array $form) {
        $this->body = http_build_query($form);
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        return $this;
    }

    public function basicAuth($user, $pass) {
        $this->auth = 'Basic ' . base64_encode("{$user}:{$pass}");
        return $this;
    }

    public function bearerAuth($token) {
        $this->auth = "Bearer {$token}";
        return $this;
    }

    public function send() {
        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->method);
        if ($this->body) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->body);
        }
        $curlHeaders = [];
        foreach ($this->headers as $key => $value) {
            $curlHeaders[] = "{$key}: {$value}";
        }
        if ($this->auth) {
            $curlHeaders[] = "Authorization: {$this->auth}";
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $curlHeaders);
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headers = curl_getinfo($ch, CURLINFO_HEADER_OUT);
        curl_close($ch);

        if ($response === false) {
            throw new Exception('HTTP request failed');
        }

        return new class($status, $response, $headers) {
            public $status;
            public $body;
            public $headers;

            public function __construct($status, $body, $headers) {
                $this->status = $status;
                $this->body = $body;
                $this->headers = $headers;
            }

            public function json() {
                return json_decode($this->body, true);
            }

            public function text() {
                return $this->body;
            }
        };
    }
}