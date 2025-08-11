<?php
namespace Rivulet\Middleware;

use Closure;
use Rivulet\Http\Request;

/**
 * Middleware Interface
 *
 * Defines the contract for HTTP middleware components that:
 * - Intercept and process HTTP requests
 * - Pass control to the next middleware in the stack
 * - Return HTTP responses
 */
interface Middleware
{
    /**
     * Process an incoming HTTP request
     *
     * @param Request $request Incoming HTTP request
     * @param Closure $next Next middleware in the pipeline
     * @return mixed HTTP response or next middleware result
     */
    public function handle(Request $request, Closure $next);
}
