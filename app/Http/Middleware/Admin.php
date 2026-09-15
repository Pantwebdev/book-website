<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->route('login');
        }


        if (Auth::guard('web')->user()->type != 1) {
            Auth::logout();
            return redirect('/')->with('error', 'Unauthorized access.');
        }

        return $next($request);
    }
}
