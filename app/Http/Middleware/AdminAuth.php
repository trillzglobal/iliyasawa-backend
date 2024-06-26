<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowedRoles = [1,2]; // Replace with the desired role(s)
//        $userRoles = json_decode(Auth::user()->user_role, true); // $roles will be an array
        $userRoles =Auth::user()->user_role; // $roles will be an array

        if (is_array($userRoles) && array_intersect($allowedRoles, $userRoles) && Auth::user()->account_type=='internal') {
            return $next($request);
        }
        return jsonResponse('Invalid Admin Credentials', [], 403);
    }
}
