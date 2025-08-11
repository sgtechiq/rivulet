<?php

namespace Rivulet;

use Rivulet\Http\Request;
use Rivulet\Http\Response;

class Controller {
    protected $request;
    protected $response;
    protected $app;

    protected $filesystem;

protected $view;
protected $mail;

protected $notification;
protected $http;

protected $session;
protected $cookie;
public function __construct(Request $request, Response $response) {
    $this->request = $request;
    $this->response = $response;
    $this->app = \Rivulet\Rivulet::getInstance();
    $this->filesystem = $this->app->make('filesystem');
    $this->view = $this->app->make('view');
    $this->mail = $this->app->make('mail');
    $this->notification = $this->app->make('notification');
    $this->http = $this->app->make('http');
    $this->session = $this->app->make('session');
    $this->cookie = $this->app->make('cookie');
}

    // Helper for JSON response
    protected function json($data, $status = 200) {
        return Response::json($data, $status);
    }

    // Helper for HTML response (using templates, implement in views group)
    protected function view($template, $data = []) {
        // Placeholder: return View::render($template, $data);
        return $this->view->render($template, $data);
    }

    // Access to validator (implement in validation group)
    protected function validate(array $data, array $rules) {
        // Placeholder: return Validator::validate($data, $rules);
    }
}