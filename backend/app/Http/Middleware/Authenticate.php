<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next, ...$guards)
    {
        if (Auth::guard()->guest()) {
            return response()->json(['message' => 'Não autorizado'], 401);
        }

        return $next($request);
    }
}
