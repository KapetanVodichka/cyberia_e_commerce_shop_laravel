<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware {
    public function handle(Request $request, Closure $next) {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['message' => 'Доступ запрещен'], 403);
        }
        return $next($request);
    }
}
