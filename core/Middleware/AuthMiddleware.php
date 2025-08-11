<?php
namespace Rivulet\Middleware;

use Closure;
use Rivulet\Auth\Authentication;
use Rivulet\Http\Request;
use Rivulet\Http\Response;

/**
 * Authentication Middleware
 *
 * Verifies Bearer tokens and authenticates requests by:
 * 1. Checking for Authorization header
 * 2. Validating JWT token
 * 3. Attaching authenticated user to request
 */
class AuthMiddleware implements Middleware
{
    /**
     * Handle incoming request
     *
     * @param Request $request HTTP request
     * @param Closure $next Next middleware handler
     * @return Response HTTP response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verify Authorization header exists and is Bearer token
        $authHeader = $request->header('Authorization');
        if (! $this->hasValidAuthHeader($authHeader)) {
            return Response::json(['error' => 'Unauthorized - Token required'], 401);
        }

        // Extract and verify token
        $token = substr($authHeader, 7);
        $user  = Authentication::verifyToken($token);

        if (! $user) {
            return Response::json(['error' => 'Unauthorized - Invalid token'], 401);
        }

        // Attach authenticated user to request
        $request->user = $user;

        return $next($request);
    }

    /**
     * Validate Authorization header format
     *
     * @param string|null $header Authorization header
     * @return bool True if valid Bearer token format
     */
    protected function hasValidAuthHeader(?string $header): bool
    {
        return $header && str_starts_with($header, 'Bearer ');
    }
}
