<?php
namespace Rivulet\Http;

use Exception;
use Rivulet\Rivulet;

/**
 * HTTP Kernel
 *
 * Core request handler that:
 * - Bootstraps the application
 * - Dispatches requests to the router
 * - Handles exceptions and errors
 */
class Kernel
{
    /**
     * @var Rivulet Application instance
     */
    protected $app;

    /**
     * Create new HTTP kernel
     *
     * @param Rivulet $app Application instance
     */
    public function __construct(Rivulet $app)
    {
        $this->app = $app;
    }

    /**
     * Handle incoming HTTP request
     *
     * @param Request $request HTTP request
     * @return Response HTTP response
     */
    public function handle(Request $request): Response
    {
        try {
            // Ensure application is bootstrapped
            $this->app->bootstrap();

            // Future middleware processing
            // $this->applyMiddleware($request);

            // Route the request
            $router   = $this->app->make('router');
            $response = $router->dispatch($request);

            return $response;
        } catch (Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Handle application exceptions
     *
     * @param Exception $e Thrown exception
     * @return Response Error response
     */
    protected function handleException(Exception $e): Response
    {
        if ($this->app->getConfig('app.debug')) {
            return Response::json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }

        return Response::json([
            'error' => 'Internal Server Error',
        ], 500);
    }

    /**
     * Apply global middleware (placeholder)
     *
     * @param Request $request HTTP request
     */
    protected function applyMiddleware(Request $request)
    {
        // To be implemented with middleware pipeline
    }
}
