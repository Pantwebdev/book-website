<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\MasterOrder;
use App\Models\CartItem;
use App\Models\User;
use App\Models\OrderStatus;

class CustomerController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $recentOrders = MasterOrder::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $totalOrders = MasterOrder::where('user_id', $user->id)->count();
        $totalSpent = MasterOrder::where('user_id', $user->id)->sum('grand_total');
        $deliveredCount = MasterOrder::where('user_id', $user->id)
        ->where('order_status_id', 7)
        ->count();
        return view('customer.dashboard', compact('user', 'recentOrders', 'totalOrders', 'totalSpent', 'deliveredCount'));
    }

    // ...existing code...
    public function orders()
    {
        $user = Auth::user();
        $orders = MasterOrder::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $totalOrders = MasterOrder::where('user_id', $user->id)->count();
        $totalSpent = MasterOrder::where('user_id', $user->id)
       ->sum('grand_total');

        $deliveredCount = MasterOrder::where('user_id', $user->id)
        ->where('order_status_id', 7)
        ->count();
        return view('customer.orders', compact('user', 'orders', 'totalOrders', 'totalSpent', 'deliveredCount'));
    }
    // ...existing code...
    public function orderDetails($order_number)
    {
        $user = Auth::user();

        $order = MasterOrder::where('order_number', $order_number)
            ->where('user_id', $user->id)
            ->with('items')
            ->firstOrFail();
        $totalOrders = MasterOrder::where('user_id', $user->id)->count();
        $totalSpent = MasterOrder::where('user_id', $user->id)->sum('grand_total');
        $deliveredCount = MasterOrder::where('user_id', $user->id)
        ->where('order_status_id', 7)
        ->count();
        return view('customer.order-details', compact('user', 'order', 'totalOrders', 'totalSpent', 'deliveredCount'));
    }
    public function trackOrdercu(Request $request)
    {
        $request->validate([
            'order_number' => 'required',
        ]);

        $order = MasterOrder::where('order_number', $request->order_number)->first();

        if (!$order) {
            return back()->with('error', 'Invalid Order Number');
        }

        return redirect()->route('customer.order.track.view', $order->order_number);
    }

    public function orderTrackView($order_number)
    {
        $user = Auth::user();
        $masterOrder = MasterOrder::where('order_number', $order_number)->with('orderStatus')->firstOrFail();
        $totalOrders = MasterOrder::where('user_id', $user->id)->count();
        $totalSpent = MasterOrder::where('user_id', $user->id)->sum('grand_total');
        $deliveredCount = MasterOrder::where('user_id', $user->id)->where('order_status_id', 7)->count();

        $statuses = OrderStatus::where('status', 1)->where('status', 1)->get();
        return view('customer.order-track', compact('user', 'masterOrder', 'totalOrders', 'totalSpent', 'deliveredCount', 'statuses'));
    }

    public function cartitem()
    {
        $user = Auth::user();
        $cartItems = CartItem::where('user_id', $user->id)->where('status', 'ordered')
            ->with('product')
            ->get();
        $totalOrders = MasterOrder::where('user_id', $user->id)->count();
        $totalSpent = MasterOrder::where('user_id', $user->id)
       ->sum('grand_total');

        $deliveredCount = MasterOrder::where('user_id', $user->id)
        ->where('order_status_id', 7)
        ->count();
        return view('customer.cart', compact('user', 'cartItems', 'totalOrders', 'totalSpent', 'deliveredCount'));
    }


    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:10',
        ]);

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully!',
        ]);
    }
}
