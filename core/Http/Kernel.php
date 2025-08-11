<?php

namespace Rivulet\Http;

use Rivulet\Rivulet;
use Exception;

class Kernel {
    protected $app;

    public function __construct(Rivulet $app) {
        $this->app = $app;
    }

    public function handle(Request $request) {
        try {
            // Boot providers if not already (for full bootstrap)
            $this->app->bootstrap();

            // Apply global middleware (to be implemented)
            // $this->applyMiddleware($request);

            // Dispatch to router
            $router = $this->app->make('router');
$response = $router->dispatch($request);

            return $response;
        } catch (Exception $e) {
            // Handle exceptions, e.g., 404 or 500
            if ($this->app->getConfig('app.debug')) {
                return Response::json(['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()], 500);
            } else {
                return Response::json(['error' => 'Internal Server Error'], 500);
            }
        }
    }

    // Placeholder for middleware application
    protected function applyMiddleware(Request $request) {
        // Implement in middleware group
    }
}