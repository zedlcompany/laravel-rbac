<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param string $permissions Pipe-separated list of permission slugs (e.g., "users.create|users.update")
     */
    public function handle(Request $request, Closure $next, string $permissions): Response
    {
        if (!$request->user()) {
            abort(401, 'Unauthenticated.');
        }

        if (!$request->user()->hasPermission($permissions)) {
            abort(403, 'You do not have the required permission to access this resource.');
        }

        return $next($request);
    }
}
