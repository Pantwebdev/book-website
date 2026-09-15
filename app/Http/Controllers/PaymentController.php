<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;
use Razorpay\Api\Api;
use App\Models\MasterOrder;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Link;
use App\Models\OrderStatus;
use App\Models\PaymentStatus;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmationMail;

class PaymentController extends Controller
{
    public function paymentSuccess(Request $request, $order_number)
    {

        if ($request->isMethod('get')) {
            return $this->handleGetPaymentSuccess($request, $order_number);
        }

        return $this->handlePostPaymentSuccess($request, $order_number);
    }

    private function handleGetPaymentSuccess(Request $request, $order_number)
    {
        try {
            \Log::info('GET Payment Success Request:', $request->all());

            $masterOrder = MasterOrder::where('order_number', $order_number)->firstOrFail();

            // Get active order statuses
            $orderStatuses = OrderStatus::where('status', 1)->pluck('id', 'order_status')->toArray();
            $paymentStatuses = PaymentStatus::pluck('id', 'payment_status')->toArray();

            // Find status IDs
            $processingStatusId = $orderStatuses['Processing'] ?? null;
            $successPaymentStatusId = $paymentStatuses['Success'] ?? null;

            if (!$processingStatusId || !$successPaymentStatusId) {
                throw new \Exception('Required statuses not found');
            }

            // Check for payment parameters
            if (!$request->has('razorpay_payment_id')
                || !$request->has('razorpay_order_id')
                || !$request->has('razorpay_signature')) {

                \Log::error('Missing payment parameters in GET request');
                return redirect()->route('payment.page', ['order_number' => $order_number])
                    ->with('error', 'Payment verification failed. Missing parameters.');
            }

            // Verify payment signature
            $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

            $attributes = [
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature,
            ];

            $api->utility->verifyPaymentSignature($attributes);

            \DB::beginTransaction();

            // Update order and payment status using IDs from database
            $masterOrder->update([
                'order_status_id' => $processingStatusId, // Change to 'Processing'
                'payment_status_id' => $successPaymentStatusId, // Change to 'Success'
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature,
                'payment_verified_at' => now(),
                'payment_collected_at' => now(),
            ]);

            // CartItem::where('master_order_id', $masterOrder->id)
            //     ->update([
            //         'status' => 'ordered'
            //     ]);
            //New Code
            $cartItems = CartItem::where('master_order_id', $masterOrder->id)
                ->where('status', 'active')
                ->get();

            // नया (user_id से भी fallback):
            $cartItems = CartItem::where('master_order_id', $masterOrder->id)
                ->where('status', 'active')
                ->get();

            // अगर master_order_id से नहीं मिले तो user_id से try करें
            if ($cartItems->isEmpty() && $masterOrder->user_id) {
                $cartItems = CartItem::where('user_id', $masterOrder->user_id)
                    ->where('status', 'active')
                    ->get();
            }

            foreach ($cartItems as $cartItem) {
                $product = Product::find($cartItem->product_id);
                if ($product) {
                    $product->decreaseStock($cartItem->qty);
                }
                $cartItem->update(['status' => 'ordered']);
            }
            //New Code
            \DB::commit();

            // Send email
            $this->sendOrderConfirmationEmail($masterOrder);
            // \App\Jobs\SendOrderConfirmationEmail::dispatch($masterOrder);
            // Remove coupon
            session()->forget('applied_coupon');

            \Log::info('GET Payment successful for order: ' . $masterOrder->order_number);

            return redirect()->route('order.confirmation', ['order_number' => $masterOrder->order_number])
                ->with('success', '🎉 Payment Successful! Order placed successfully!')
                ->with('order_placed', true);

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('GET Payment success error: ' . $e->getMessage());

            return redirect()->route('payment.page', ['order_number' => $order_number])
                ->with('error', 'Payment verification failed. ' . $e->getMessage());
        }
    }

    // private function handleGetPaymentSuccess(Request $request, $order_number)
    // {
    //     try {
    //         \Log::info('GET Payment Success Request:', $request->all());

    //         $masterOrder = MasterOrder::where('order_number', $order_number)->firstOrFail();


    //         if (!$request->has('razorpay_payment_id') ||
    //             !$request->has('razorpay_order_id') ||
    //             !$request->has('razorpay_signature')) {

    //             \Log::error('Missing payment parameters in GET request');
    //             return redirect()->route('payment.page', ['order_number' => $order_number])
    //                 ->with('error', 'Payment verification failed. Missing parameters.');
    //         }


    //         $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

    //         $attributes = [
    //             'razorpay_order_id' => $request->razorpay_order_id,
    //             'razorpay_payment_id' => $request->razorpay_payment_id,
    //             'razorpay_signature' => $request->razorpay_signature
    //         ];


    //         $api->utility->verifyPaymentSignature($attributes);

    //         \DB::beginTransaction();


    //         $masterOrder->update([
    //             'order_status_id' => 2,
    //             'payment_status_id' => 3,
    //             'razorpay_order_id' => $request->razorpay_order_id,
    //             'razorpay_payment_id' => $request->razorpay_payment_id,
    //             'razorpay_signature' => $request->razorpay_signature,
    //             'payment_verified_at' => now(),
    //             'payment_collected_at' => now()
    //         ]);
    //         CartItem::where('master_order_id', $masterOrder->id)
    //         ->update([
    //             'status' => 'ordered'
    //         ]);
    //         \DB::commit();


    //         $this->sendOrderConfirmationEmail($masterOrder);


    //         // if (auth()->check()) {
    //         //     \App\Models\CartItem::where('user_id', auth()->id())->delete();
    //         // }


    //         session()->forget('applied_coupon');

    //         \Log::info('GET Payment successful for order: ' . $masterOrder->order_number);

    //         return redirect()->route('order.confirmation', ['order_number' => $masterOrder->order_number])
    //             ->with('success', '🎉 Payment Successful! Order placed successfully!')
    //             ->with('order_placed', true);

    //     } catch (\Exception $e) {
    //         \DB::rollBack();
    //         \Log::error('GET Payment success error: ' . $e->getMessage());

    //         return redirect()->route('payment.page', ['order_number' => $order_number])
    //             ->with('error', 'Payment verification failed. ' . $e->getMessage());
    //     }
    // }





    private function sendOrderConfirmationEmail($order)
    {
        try {

            $orderWithRelations = $order->load([
                'items',
                'orderStatus',
                'paymentStatus',
                'paymentMethod',
            ]);


            Mail::to($orderWithRelations->email)->send(new OrderConfirmationMail($orderWithRelations));


            $adminEmail = env('ADMIN_EMAIL', 'info@ajhuie.com');
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new OrderConfirmationMail($orderWithRelations, true));
            }

            $orderWithRelations->update(['email_sent' => 1]);

            \Log::info("Order confirmation email sent for order: " . $order->order_number);
        } catch (\Exception $e) {
            \Log::error('Email sending error: ' . $e->getMessage());
        }
    }

    public function paymentFailure(Request $request, $order_number)
    {
        try {
            $masterOrder = MasterOrder::where('order_number', $order_number)->firstOrFail();

            // Payment fail होने पर cart items की status वापस 'active' करें
            CartItem::where('master_order_id', $masterOrder->id)
                ->update([
                    'status' => 'active',
                    'master_order_id' => null,
                ]);

            $masterOrder->update([
                'payment_status_id' => 4, // Failed
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Payment failed',
                ]);
            }

            return redirect()->route('checkout')
                ->with('error', 'Payment failed. Please try again.');

        } catch (\Exception $e) {
            Log::error('Payment failure error: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Error processing payment',
                ]);
            }

            return redirect()->route('checkout')->with('error', 'Error processing payment.');
        }
    }


}
