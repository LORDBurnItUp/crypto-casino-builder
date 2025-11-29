<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (!$request->user()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Check if user is admin
        // You can add an 'is_admin' column to users table
        // or check if user email matches admin email
        $adminEmails = ['admin@yourdomain.com', 'superadmin@yourdomain.com'];

        if (!in_array($request->user()->email, $adminEmails)) {
            return response()->json(['message' => 'Unauthorized - Admin access required'], 403);
        }

        return $next($request);
    }
}
