<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        // Check if the user is authenticated
        if (!$user) {
            return redirect()->route('login');
        }

        // if the user is not admin or superadmin, redirect back
        $allowedRoles = ['admin', 'superadmin'];
        if (!in_array($user->role, $allowedRoles)) {
            return redirect()->back();
        }

        return $next($request);
    }
}
