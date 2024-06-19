<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Managerials
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowedRoles = [2]; // Replace with the desired role(s)
        $userRoles = json_decode(Auth::user()->user_role, true); // $roles will be an array

        if (is_array($userRoles) && array_intersect($allowedRoles, $userRoles)) {
            return $next($request);
        }
        return jsonResponse('Invalid Managerial Credentials', [], 403);
    }
}
