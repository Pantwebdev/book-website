<?php

namespace App\Providers;

use App\Models\Setting;
use App\Models\CartItem;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}


    public function boot()
    {

        view()->composer('*', function ($view) {
            $cartCount = 0;

            if (Auth::guard('customer')->check()) {
                $cartCount = CartItem::where('user_id', Auth::guard('customer')->id())->where('status', 'active')->sum('qty');
            } else {

                $guestToken = Cookie::get('guest_token');
                if ($guestToken) {
                    $cartCount = CartItem::where('guest_token', $guestToken)->where('status', 'active')->sum('qty');
                }
            }

            $view->with('cartCount', $cartCount);
        });
        view()->composer('*', function ($view) {
            $setting = Setting::first();
            $view->with('setting', $setting);
        });


        view()->composer('*', function ($view) {
            $wishcount = 0;

            $guestToken = Cookie::get('guest_token');
            if ($guestToken) {
                $wishcount = \App\Models\Wishlist::where('guest_token', $guestToken)->count();
            }

            $view->with('wishcount', $wishcount);
        });



    }
}
