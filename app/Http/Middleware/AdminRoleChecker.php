<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminRoleChecker
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ((int)Auth::user()->role_id === 1) {
            return $next($request);
        }

        return response([
            'status' => 'error',
            'message' => 'You are not accept to access this information'
        ], 403);
    }
}
