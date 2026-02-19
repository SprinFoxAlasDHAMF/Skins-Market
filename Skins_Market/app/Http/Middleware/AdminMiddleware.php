<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->role == 'admin') {
            return $next($request);
        }

        abort(403, 'No tienes permisos para acceder a esta página.');
    }
}

