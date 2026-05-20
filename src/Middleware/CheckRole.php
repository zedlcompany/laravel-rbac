<?php

namespace Zedlcompany\LaravelRbac\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param string $roles Pipe-separated list of role slugs (e.g., "admin|manager")
     */
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        if (!$request->user()) {
            abort(401, 'Unauthenticated.');
        }

        if (!$request->user()->hasRole($roles)) {
            abort(403, 'You do not have the required role to access this resource.');
        }

        return $next($request);
    }
}
