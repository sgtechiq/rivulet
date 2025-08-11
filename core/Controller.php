<?php
namespace Rivulet;

use Rivulet\Http\Request;
use Rivulet\Http\Response;

/**
 * Base Controller Class
 *
 * Provides common functionality and service access
 * for application controllers to extend.
 */
class Controller
{
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

    /**
     * Initialize controller with dependencies
     * @param Request $request HTTP request
     * @param Response $response HTTP response
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request      = $request;
        $this->response     = $response;
        $this->app          = \Rivulet\Rivulet::getInstance();
        $this->filesystem   = $this->app->make('filesystem');
        $this->view         = $this->app->make('view');
        $this->mail         = $this->app->make('mail');
        $this->notification = $this->app->make('notification');
        $this->http         = $this->app->make('http');
        $this->session      = $this->app->make('session');
        $this->cookie       = $this->app->make('cookie');
    }

    /**
     * Return JSON response
     * @param mixed $data Response data
     * @param int $status HTTP status code
     */
    protected function json($data, $status = 200)
    {
        return Response::json($data, $status);
    }

    /**
     * Render view template
     * @param string $template Template name
     * @param array $data View data
     */
    protected function view($template, $data = [])
    {
        return $this->view->render($template, $data);
    }

    /**
     * Validate input data
     * @param array $data Data to validate
     * @param array $rules Validation rules
     */
    protected function validate(array $data, array $rules)
    {
        // Validation implementation placeholder
    }
}
