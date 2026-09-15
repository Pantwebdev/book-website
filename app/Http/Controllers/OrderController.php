<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\slider;
use App\Models\Category;
use App\Models\Sizecolor;
use App\Models\CartItem;
use App\Models\Subcategory;
use App\Models\ChildSubcategory;
use App\Models\Product;
use App\Models\Link;
use App\Models\DiscountCode;
use App\Models\CheckAvailability;
use App\Models\Wishlist;
use App\Models\Review;
use App\Models\Tax;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MasterOrder;
use App\Models\MasterOrderItem;
use App\Models\Blog;
use App\Models\PaymentMethod;
use App\Models\OrderStatus;
use App\Models\PaymentStatus;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmationMail;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Setting;

class OrderController extends Controller
{
    public function checkout()
    {
        // ✅ पहले check करें कि cart empty तो नहीं है
        if (auth()->guard('customer')->check()) {
            $cartItems = CartItem::with('product')->where('user_id', auth()->guard('customer')->id())
                ->where('status', 'active')
                ->get();
        } else {
            $guestToken = Cookie::get('guest_token');
            $cartItems = $guestToken ? CartItem::with('product')->where('guest_token', $guestToken)
                ->where('status', 'active')
                ->get() : collect();
        }

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.view')->with('error', 'Your cart is empty. Please add products to checkout.');
        }

        $navcategories = Category::with(['subcategories.childSubcategories'])
            ->where('status', 1)
            ->get();
        $linkss = Link::where('status', 1)->get();

        // ✅ Check if admin is logged in
        if (Auth::guard('web')->check()) {
            // Admin logged in hai, customer logout karwao
            Auth::guard('web')->logout();
            session()->flush();

            // Redirect to cart with login modal open
            return redirect()->route('cart.view')
                ->with('open_login_modal', true)
                ->with('warning', 'Admin session closed. Please login as customer to checkout.');
        }



        $totalMRP = 0;
        $finalTotal = 0;
        $itemCount = 0;

        foreach ($cartItems as $item) {
            $totalMRP += $item->mrp_price * $item->qty;
            $finalTotal += $item->price * $item->qty;
            $itemCount += $item->qty;
        }

        $totalDiscountAmount = $totalMRP - $finalTotal;
        if ($totalDiscountAmount < 0) {
            $totalDiscountAmount = 0;
        }

        $couponDiscount = session('applied_coupon.discount', 0);
        $finalTotalAfterCoupon = max(0, $finalTotal - $couponDiscount);

        $totalShipping = 0;
        foreach ($cartItems as $item) {
            $prod = $item->product;
            if ($prod && $prod->shipping_type == 1) {
                $totalShipping += ($prod->shipping_charge ?? 0) * $item->qty;
            }
        }

        $grandTotal = $finalTotalAfterCoupon + $totalShipping;

        $paymentMethods = PaymentMethod::where('status', 1)->get();

        // ✅ Get customer data if logged in
        $customer = null;
        if (Auth::guard('customer')->check()) {
            $customer = Auth::guard('customer')->user();
        }

        return view('checkout', compact(
            'navcategories',
            'linkss',
            'cartItems',
            'totalMRP',
            'finalTotal',
            'itemCount',
            'totalDiscountAmount',
            'totalShipping',
            'couponDiscount',
            'grandTotal',
            'finalTotalAfterCoupon',
            'paymentMethods',
            'customer',
        ));
    }


    public function fetchCityState(Request $request)
    {
        try {
            $request->validate([
                'pincode' => 'required|digits:6',
            ]);

            $pincode = $request->pincode;


            $deliveryAvailable = CheckAvailability::where('pincode', $pincode)
                ->where('status', 1)
                ->exists();

            if (!$deliveryAvailable) {
                return response()->json([
                    'success' => false,
                    'message' => 'Delivery not available at this pincode. Please enter a different pincode.',
                    'delivery_available' => false,
                ]);
            }


            $apiResponse = $this->getCityStateFromAPI($pincode);

            if ($apiResponse['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Delivery available at this pincode!',
                    'delivery_available' => true,
                    'city' => $apiResponse['city'],
                    'state' => $apiResponse['state'],
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to fetch city/state. Please enter manually.',
                    'delivery_available' => true,
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Fetch city state error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error fetching location details. Please try again.',
            ]);
        }
    }


    private function getCityStateFromAPI($pincode)
    {

        $url = "https://api.postalpincode.in/pincode/{$pincode}";


        $context = stream_context_create([
            'http' => [
                'timeout' => 5,
                'header' => "User-Agent: Mozilla/5.0\r\n",
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);

        $response = @file_get_contents($url, false, $context);

        if ($response !== false) {
            $data = json_decode($response, true);

            if (isset($data[0]['Status']) && $data[0]['Status'] === 'Success') {
                $postOffice = $data[0]['PostOffice'][0] ?? null;

                if ($postOffice) {
                    return [
                        'success' => true,
                        'city' => $postOffice['District'] ?? $postOffice['Circle'] ?? '',
                        'state' => $postOffice['State'] ?? '',
                    ];
                }
            }
        }


        return [
            'success' => true,
            'city' => '',
            'state' => '',
            'note' => 'Please enter manually',
        ];
    }

    public function checkPincode(Request $request)
    {
        $request->validate([
            'pincode' => 'required|digits:6',
        ]);

        $pincode = CheckAvailability::where('pincode', $request->pincode)
            ->where('status', 1)
            ->first();

        if ($pincode) {
            return response()->json([
                'success' => true,
                'message' => 'Delivery available at this pincode!',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Delivery not available at this pincode.',
        ]);
    }
    public function placeOrder(Request $request)
    {
        \DB::beginTransaction();
        try {
            \Log::info('Place order request received', $request->all());

            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'nullable|string|max:255',
                'email' => 'required|email',
                'phone' => 'required|string|max:10',
                'address' => 'required|string',
                'city' => 'required|string',
                'state' => 'required|string',
                'pincode' => 'required|string|max:6',
                'payment_method_id' => 'required|exists:payment_method,id',
            ]);

            // Get active statuses from database (only where status = 1)
            $orderStatuses = OrderStatus::where('status', 1)->pluck('id', 'order_status')->toArray();

            // Find 'Order Placed' status ID from active statuses
            $orderPlacedId = $orderStatuses['Order Placed'] ?? null;

            if (!$orderPlacedId) {
                throw new \Exception('Order Placed status not found in active statuses');
            }

            // Get payment status IDs
            $paymentStatuses = PaymentStatus::pluck('id', 'payment_status')->toArray();

            // Rest of your existing code for cart items...
            if (auth()->check()) {
                $cartItems = CartItem::where('user_id', auth()->id())->where('status', 'active')->get();
                $userId = auth()->id();
                $guestToken = null;
            } else {
                $guestToken = Cookie::get('guest_token');
                $cartItems = $guestToken ? CartItem::where('guest_token', $guestToken)->where('status', 'active')->get() : collect();
                $userId = null;
            }

            \Log::info('Cart items count: ' . $cartItems->count());

            if ($cartItems->isEmpty()) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Your cart is empty.',
                    ], 400);
                }
                return redirect()->route('cart.view')->with('error', 'Your cart is empty.');
            }

            // Calculate totals (existing code)...
            $subtotal = 0;
            $totalMRP = 0;

            foreach ($cartItems as $item) {
                $subtotal += $item->price * $item->qty;
                $totalMRP += $item->mrp_price * $item->qty;
            }

            $discountAmount = $totalMRP - $subtotal;
            $couponDiscount = $request->coupon_discount ?? 0;
            $finalTotalBeforeTax = max(0, $subtotal - $couponDiscount);


            $totalShipping = 0;
            foreach ($cartItems as $cartItem) {
                $prod = Product::find($cartItem->product_id);
                if ($prod && $prod->shipping_type == 1) {
                    $totalShipping += ($prod->shipping_charge ?? 0) * $cartItem->qty;
                }
            }
            $taxAmount = 0; // keep as 0, no tax
            $grandTotal = $finalTotalBeforeTax + $totalShipping;
            // Get payment method
            $paymentMethod = PaymentMethod::find($request->payment_method_id);

            if (!$paymentMethod) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Invalid payment method selected.',
                    ], 400);
                }
                throw new \Exception('Invalid payment method selected');
            }

            $isCOD = $paymentMethod->isCOD();

            // SET ORDER STATUS TO 'Order Placed' FOR BOTH COD AND ONLINE
            $orderStatusId = $orderPlacedId; // Always use 'Order Placed' for new orders

            // Set payment status based on payment method
            if ($isCOD) {
                // For COD, payment status is 'Pending'
                $paymentStatusId = $paymentStatuses['Pending'] ?? 1;
            } else {
                // For Online, payment status is 'Initiated'
                $paymentStatusId = $paymentStatuses['Initiated'] ?? 2;
            }

            // Create master order
            $masterOrderData = [
                'user_id' => $userId,
                'guest_token' => $guestToken,
                'order_number' => MasterOrder::generateOrderNumber(),

                // Use dynamic IDs from database
                'order_status_id' => $orderStatusId,
                'payment_status_id' => $paymentStatusId,
                'payment_method_id' => $request->payment_method_id,

                // Customer info (existing code)...
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'address2' => $request->address2,
                'city' => $request->city,
                'state' => $request->state,
                'pincode' => $request->pincode,
                'country' => $request->country ?? 'India',

                // Shipping info...
                'shipping_first_name' => $request->shipping_first_name,
                'shipping_last_name' => $request->shipping_last_name,
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'shipping_state' => $request->shipping_state,
                'shipping_pincode' => $request->shipping_pincode,
                'shipping_phone' => $request->shipping_phone,

                // Amounts...
                'subtotal' => $subtotal,
                'total_mrp' => $totalMRP,
                'discount_amount' => $discountAmount,
                'coupon_discount' => $couponDiscount,
                'coupon_code' => $request->coupon_code,
                'tax_amount'      => $taxAmount,
                'shipping_amount' => $totalShipping,
                'grand_total'     => $grandTotal,
            ];

            $masterOrder = MasterOrder::create($masterOrderData);
            \Log::info('Master order created: ' . $masterOrder->id);

            // Create order items
            foreach ($cartItems as $cartItem) {
                $masterOrderItem = new MasterOrderItem([
                    'master_order_id' => $masterOrder->id,
                    'product_id' => $cartItem->product_id,
                    'product_name' => $cartItem->name ?? 'Unknown Product',
                    'sku' => $cartItem->sku,
                    'qty' => $cartItem->qty,
                    'price' => $cartItem->price,
                    'mrp_price' => $cartItem->mrp_price,
                    'discount' => $cartItem->discount,
                    'color' => $cartItem->color,
                    'size' => $cartItem->size,
                    'image' => $cartItem->image,
                ]);

                $masterOrderItem->calculateTotals();
                $masterOrder->items()->save($masterOrderItem);
            }

            \Log::info('All order items created');

            // Handle COD and Online differently
            if ($isCOD) {
                // Update cart items
                // foreach ($cartItems as $cartItem) {
                //     $cartItem->update([
                //         'status' => 'ordered',
                //         'master_order_id' => $masterOrder->id
                //     ]);
                // }
                foreach ($cartItems as $cartItem) {
                    $product = Product::find($cartItem->product_id);
                    $product->decreaseStock($cartItem->qty);

                    $cartItem->update([
                        'status' => 'ordered',
                        'master_order_id' => $masterOrder->id,
                    ]);
                }
                session()->forget('applied_coupon');

                \Log::info('COD order completed, redirecting to confirmation');

                \DB::commit();

                // Send email
                $this->sendOrderConfirmationEmail($masterOrder);
                //\App\Jobs\SendOrderConfirmationEmail::dispatch($masterOrder);
                // Mail::to($order->email)->send(new OrderConfirmationMail($order, false));
                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'is_cod' => true,
                        'redirect' => route('order.confirmation', ['order_number' => $masterOrder->order_number]),
                    ]);
                }

                return redirect()->route('order.confirmation', ['order_number' => $masterOrder->order_number])
                         ->with('success', '🎉 Order Placed Successfully!')
                         ->with('order_placed', true);
            } else {
                // Online payment
                //New Code
                foreach ($cartItems as $cartItem) {
                    $cartItem->update([
                        'master_order_id' => $masterOrder->id,
                        // status remains 'active'
                    ]);
                }
                //New Code

                $razorpayOrderId = $this->createRazorpayOrder($masterOrder);

                if (!$razorpayOrderId) {
                    // Payment fail होने पर status वापस active करें
                    foreach ($cartItems as $cartItem) {
                        $cartItem->update([
                            'status' => 'active',
                            'master_order_id' => null,
                        ]);
                    }

                    if ($request->ajax()) {
                        return response()->json([
                            'success' => false,
                            'error' => 'Failed to create Razorpay order',
                        ], 500);
                    }
                    throw new \Exception('Failed to create Razorpay order');
                }

                \DB::commit();

                \Log::info('Razorpay order created, returning details for direct payment');

                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'is_cod' => false,
                        'is_online' => true,
                        'razorpay_order_id' => $razorpayOrderId,
                        'amount' => $masterOrder->grand_total * 100,
                        'razorpay_key' => env('RAZORPAY_KEY'),
                        'order_id' => $masterOrder->id,
                        'order_number' => $masterOrder->order_number,
                        'customer_name' => $masterOrder->first_name . ' ' . ($masterOrder->last_name ?? ''),
                        'customer_email' => $masterOrder->email,
                        'customer_phone' => $masterOrder->phone,
                        'redirect' => route('order.confirmation', ['order_number' => $masterOrder->order_number]),
                    ]);
                }

                return redirect()->route('order.confirmation', [
                    'order_number' => $masterOrder->order_number,
                    'show_payment_modal' => true,
                ]);
            }

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Order placement error: ' . $e->getMessage());

            // Error होने पर सभी cart items की status वापस active करें
            if (isset($cartItems)) {
                foreach ($cartItems as $cartItem) {
                    $cartItem->update([
                        'status' => 'active',
                        'master_order_id' => null,
                    ]);
                }
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Error placing order: ' . $e->getMessage(),
                ], 500);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error placing order. Please try again.');
        }
    }





    private function createRazorpayOrder($masterOrder)
    {
        try {
            $api = new \Razorpay\Api\Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

            $razorpayOrder = $api->order->create([
                'receipt' => 'order_rcpt_' . $masterOrder->order_number,
                'amount' => $masterOrder->grand_total * 100,
                'currency' => 'INR',
                'payment_capture' => 1,
            ]);


            $masterOrder->update([
                'razorpay_order_id' => $razorpayOrder->id,
                'payment_status_id' => 2,
                'payment_initiated_at' => now(),
            ]);

            return $razorpayOrder->id;

        } catch (\Exception $e) {
            \Log::error('Create Razorpay order error: ' . $e->getMessage());
            return null;
        }
    }

    private function sendOrderConfirmationEmail($masterOrder)
    {
        try {

            $orderWithRelations = $masterOrder->load([
                'items',
                'orderStatus',
                'paymentStatus',
                'paymentMethod',
            ]);


            Mail::to($orderWithRelations->email)->send(new OrderConfirmationMail($orderWithRelations));


            $adminEmail = env('ADMIN_EMAIL', 'sachinfreetesting@gmail.com');
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new OrderConfirmationMail($orderWithRelations, true));
            }

            \Log::info("Order confirmation email sent for order: " . $masterOrder->order_number);
        } catch (\Exception $e) {
            \Log::error('Email sending error: ' . $e->getMessage());
        }
    }


    public function orderConfirmation($orderNumber)
    {
        try {

            $masterOrder = MasterOrder::with([
                'items',
                'orderStatus',
                'paymentStatus',
                'paymentMethod',
            ])->where('order_number', $orderNumber)->firstOrFail();


            $currentStatus = [
                'status_id' => $masterOrder->order_status_id,
                'status_label' => $masterOrder->orderStatus ? $masterOrder->orderStatus->order_status : 'Unknown',
                'payment_status_id' => $masterOrder->payment_status_id,
                'payment_status_label' => $masterOrder->paymentStatus ? $masterOrder->paymentStatus->payment_status : 'Unknown',
            ];


            $statusHistory = $masterOrder->status_history ?? [];

            $navcategories = Category::with(['subcategories.childSubcategories'])
                ->where('status', 1)
                ->get();
            $linkss = Link::where('status', 1)->get();


            if (session('success')) {

            } elseif (session('order_placed')) {
                session()->flash('success', 'Order placed successfully!');
            }

            return view('order-confirmation', compact(
                'masterOrder',
                'navcategories',
                'linkss',
                'currentStatus',
                'statusHistory',
            ));

        } catch (\Exception $e) {
            \Log::error('Order confirmation error: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Order not found.');
        }
    }
    public function deleteCartItem($id)
    {
        try {
            $cartItem = CartItem::findOrFail($id);

            // Authorization check
            if (auth()->guard('customer')->check()) {
                if ($cartItem->user_id != auth()->guard('customer')->id()) {
                    return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
                }
            } else {
                $guestToken = Cookie::get('guest_token');
                if ($cartItem->guest_token != $guestToken) {
                    return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
                }
            }

            $cartItem->delete();

            // Recalculate totals
            if (auth()->guard('customer')->check()) {
                $cartItems = CartItem::where('user_id', auth()->guard('customer')->id())
                    ->where('status', 'active')->get();
            } else {
                $guestToken = Cookie::get('guest_token');
                $cartItems = CartItem::where('guest_token', $guestToken)
                    ->where('status', 'active')->get();
            }

            $finalTotal = $cartItems->sum(fn($i) => $i->price * $i->qty);
            $totalMRP   = $cartItems->sum(fn($i) => $i->mrp_price * $i->qty);
            $itemCount  = $cartItems->sum('qty');

            $couponDiscount = session('applied_coupon.discount', 0);
            $finalTotalAfterCoupon = max(0, $finalTotal - $couponDiscount);


            $totalShipping = 0;
            foreach ($cartItems as $item) {
                $prod = $item->product ?? Product::find($item->product_id);
                if ($prod && $prod->shipping_type == 1) {
                    $totalShipping += ($prod->shipping_charge ?? 0) * $item->qty;
                }
            }
            $taxAmount  = 0;
            $grandTotal = $finalTotalAfterCoupon + $totalShipping;

            return response()->json([
                'success'    => true,
                'message'    => 'Item removed successfully',
                'cart_empty' => $cartItems->isEmpty(),
                'item_count' => $itemCount,
                'totals'     => [
                    'final_total'  => $finalTotal,
                    'grand_total'  => $grandTotal,
                    'shipping_amount' => $totalShipping,
                    'total_mrp'    => $totalMRP,
                ],
            ]);

        } catch (\Exception $e) {
            \Log::error('Cart delete error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error removing item'], 500);
        }
    }

    public function downloadInvoice($orderId)
    {
        try {
            // Load all relationships needed for invoice
            $masterOrder = MasterOrder::with([
                'items',
                'orderStatus',
                'paymentStatus',
                'paymentMethod',
            ])->findOrFail($orderId);

            if (auth()->check()) {
                if ($masterOrder->user_id != auth()->id()) {
                    abort(403, 'Unauthorized access');
                }
            } else {
                $guestToken = Cookie::get('guest_token');
                if ($masterOrder->guest_token != $guestToken) {
                    abort(403, 'Unauthorized access');
                }
            }

            // Prepare items with images
            $itemsWithImages = [];
            foreach ($masterOrder->items as $item) {
                $itemData = $item->toArray();

                $imagePath = public_path('userassets/image/product/' . $item->image);
                if (file_exists($imagePath)) {
                    $imageData = base64_encode(file_get_contents($imagePath));
                    $itemData['image_base64'] = 'data:image/' . pathinfo($imagePath, PATHINFO_EXTENSION) . ';base64,' . $imageData;
                } else {
                    $placeholderPath = public_path('userassets/image/product/placeholder.png');
                    if (file_exists($placeholderPath)) {
                        $imageData = base64_encode(file_get_contents($placeholderPath));
                        $itemData['image_base64'] = 'data:image/png;base64,' . $imageData;
                    } else {
                        $itemData['image_base64'] = null;
                    }
                }

                $itemsWithImages[] = $itemData;
            }


            $orderStatus = $masterOrder->orderStatus ? $masterOrder->orderStatus->order_status : 'Pending';
            $paymentStatus = $masterOrder->paymentStatus ? $masterOrder->paymentStatus->payment_status : 'Pending';
            $paymentMethod = $masterOrder->paymentMethod ? $masterOrder->paymentMethod->description : 'Unknown';
            $setting = Setting::first();
            $data = [
                'order' => $masterOrder,
                'items' => $itemsWithImages,
                'orderStatus' => $orderStatus,
                'paymentStatus' => $paymentStatus,
                'paymentMethod' => $paymentMethod,
                'company' => [
                    'name' => 'Ajhuie Book Store',
                    'address' => $setting?->address ?? '123 Street, City',
                    'phone'   => $setting?->phone ?? '+91-XXXXXX-XXXX',
                    'email'   => $setting?->email ?? 'info@yourstore.com',
                    'gstin' => '########',
                    'website' => env('APP_URL', 'https://yourstore.com'),
                ],
            ];

            $options = new Options();
            $options->set('defaultFont', 'DejaVu Sans');
            $options->set('isRemoteEnabled', true);
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isPhpEnabled', true);

            $dompdf = new Dompdf($options);

            $html = view('pdf.invoice', $data)->render();

            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream('invoice-' . $masterOrder->order_number . '.pdf');

        } catch (\Exception $e) {
            \Log::error('Invoice download error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to generate invoice. Please try again.');
        }
    }



    public function trackByOrderNumber(Request $request)
    {
        $request->validate([
            'order_number' => 'required',

        ]);

        $order = MasterOrder::where('order_number', $request->order_number)

            ->first();

        if (!$order) {
            return back()->with('error', 'Order not found!');
        }

        return redirect()->route('order.track', $order->id);
    }




    public function trackOrder($order_number)
    {
        try {
            $masterOrder = MasterOrder::with([
                'items',
                'orderStatus',
                'paymentStatus',
                'paymentMethod',
            ])->where('order_number', $order_number)->firstOrFail();

            if (auth()->check()) {
                if ($masterOrder->user_id != auth()->id()) {
                    abort(403, 'Unauthorized access');
                }
            } else {
                $guestToken = Cookie::get('guest_token');
                if ($masterOrder->guest_token != $guestToken) {
                    abort(403, 'Unauthorized access');
                }
            }

            $navcategories = Category::with(['subcategories.childSubcategories'])
                ->where('status', 1)
                ->get();
            $linkss = Link::where('status', 1)->get();

            // ✅ Database से active statuses लाओ
            $activeStatuses = OrderStatus::where('status', 1)->get();

            return view('order-track', compact(
                'masterOrder',
                'navcategories',
                'linkss',
                'activeStatuses',   // ✅ view को pass करो
            ));

        } catch (\Exception $e) {
            \Log::error('Order tracking error: ' . $e->getMessage());
            return redirect()->route('index')->with('error', 'Order not found.');
        }
    }


}
