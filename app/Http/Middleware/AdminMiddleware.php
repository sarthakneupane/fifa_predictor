<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     * Only allows admin users through; redirects others with an error.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden.'], 403);
            }
            abort(403, 'Access denied. Admin area only.');
        }

        return $next($request);
    }
}