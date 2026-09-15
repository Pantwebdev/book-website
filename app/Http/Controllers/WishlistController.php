<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class WishlistController extends Controller
{
    public function toggleWishlist(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
            ]);

            $product = Product::findOrFail($request->product_id);

            // Check if user is authenticated or guest
            if (auth()->check()) {
                $userId = auth()->id();
                $guestToken = null;
            } else {
                $userId = null;
                $guestToken = $this->getGuestToken();
            }

            // Check if already in wishlist
            $existingWishlist = Wishlist::when($userId, function ($query) use ($userId) {
                return $query->where('user_id', $userId);
            })
            ->when($guestToken, function ($query) use ($guestToken) {
                return $query->where('guest_token', $guestToken);
            })
            ->where('product_id', $request->product_id)
            ->first();

            if ($existingWishlist) {
                // Remove from wishlist
                $existingWishlist->delete();
                $action = 'removed';
                $message = 'Product removed from wishlist';
            } else {
                // Add to wishlist
                Wishlist::create([
                    'user_id' => $userId,
                    'guest_token' => $guestToken,
                    'product_id' => $request->product_id,
                ]);
                $action = 'added';
                $message = 'Product added to wishlist';
            }

            // Get updated wishlist count
            $wishlistCount = $this->getWishlistCount();

            return response()->json([
                'status' => 'success',
                'action' => $action,
                'message' => $message,
                'wishlist_count' => $wishlistCount,
            ]);

        } catch (\Exception $e) {
            \Log::error('Wishlist toggle error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong!',
            ], 500);
        }
    }

    public function wishlistCount()
    {
        $count = $this->getWishlistCount();
        return response()->json(['count' => $count]);
    }

    private function getWishlistCount()
    {
        if (auth()->check()) {
            return Wishlist::where('user_id', auth()->id())->count();
        } else {
            $guestToken = Cookie::get('guest_token');
            return $guestToken ? Wishlist::where('guest_token', $guestToken)->count() : 0;
        }
    }

    private function getGuestToken()
    {
        $guestToken = Cookie::get('guest_token');
        if (!$guestToken) {
            $guestToken = Str::random(32);
            Cookie::queue('guest_token', $guestToken, 60 * 24 * 365 * 10);
        }
        return $guestToken;
    }
}
