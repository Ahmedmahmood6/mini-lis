<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        $userRole = $request->user()->role;

        // Admin has full access, or check if user's role is in the allowed roles list
        if ($userRole === 'admin' || in_array($userRole, $roles, true)) {
            return $next($request);
        }

        abort(403, 'Unauthorized access.');
    }
}
