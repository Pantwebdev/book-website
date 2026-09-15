<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Customer
{
    /**
     * Handle an incoming request.
     */
    // Customer.php middleware में
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('customer')->check()) {
            // यहाँ अलग login page नहीं, cart page पर redirect करें
            return redirect()->route('cart.view')->with('open_login_modal', true);
        }

        if (Auth::guard('customer')->user()->type != 2) {
            Auth::guard('customer')->logout();
            return redirect('/');
        }

        return $next($request);
    }
}
