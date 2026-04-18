<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /*
     * Checks that the authenticated user has the required role.
     * Usage in routes: ->middleware('role:admin')
     * Usage in controllers: $this->middleware('role:admin')
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check() || auth()->user()->role !== $role) {
            abort(403, 'You do not have permission to access this area.');
        }

        return $next($request);
    }
}
