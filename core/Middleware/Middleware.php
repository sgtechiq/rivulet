<?php

namespace Rivulet\Middleware;

use Rivulet\Http\Request;
use Closure;

interface Middleware {
    public function handle(Request $request, Closure $next);
}