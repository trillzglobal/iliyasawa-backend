<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles):Response
    {
        if (!Auth::check()) {
            return jsonResponse('Unauthorized action.', [], 403);
        }

        $userRoles = Auth::user()->user_role;

        if (!array_intersect($userRoles, $roles)) {
            return jsonResponse('Unauthorized action.', [], 403);
        }

        return $next($request);
    }
}
