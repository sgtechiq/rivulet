<?php

namespace Rivulet\Middleware;

use Rivulet\Http\Request;
use Rivulet\Http\Response;
use Rivulet\Auth\Authentication;
use Closure;

class AuthMiddleware implements Middleware {
    public function handle(Request $request, Closure $next) {
        $authHeader = $request->header('Authorization');
        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return Response::json(['error' => 'Unauthorized'], 401);
        }

        $token = substr($authHeader, 7);
        $user = Authentication::verifyToken($token);

        if (!$user) {
            return Response::json(['error' => 'Invalid token'], 401);
        }

        $request->user = $user;

        return $next($request);
    }
}