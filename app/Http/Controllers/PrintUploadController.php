<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Link;
use App\Models\PrintOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Razorpay\Api\Api;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Facades\Mail;
use App\Jobs\SendOrderMailJob;

class PrintUploadController extends Controller
{
    public function index()
    {
        $navcategories = Category::with(['subcategories.childSubcategories'])->where('status', 1)->get();
        $linkss = Link::where('status', 1)->get();
        $customer = Auth::guard('customer')->user();
        return view('upload', compact('navcategories', 'linkss', 'customer'));
    }


    private function generateOrderNumber()
    {
        do {
            $orderNumber = 'PRT-' . strtoupper(Str::random(10));
        } while (PrintOrder::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }
    // New method for 50% payment initiation
    public function initiatePayment(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:pdf|max:20000',
                'print_type' => 'required|in:black,color',
                'copies' => 'required|integer|min:1',
                'paper_size' => 'nullable|string',
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'phone' => 'required|string|size:10',
            ]);

            DB::beginTransaction();

            // Upload file
            $file = $request->file('file');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/print_orders'), $filename);

            // Get page count
            $parser = new Parser();
            $pdf = $parser->parseFile(public_path('uploads/print_orders/' . $filename));
            $pages = count($pdf->getPages());

            if ($pages < 50) {
                return response()->json([
                    'success' => false,
                    'errors' => ['file' => ['Minimum 50 pages required. Your PDF has only ' . $pages . ' pages.']],
                ], 422);
            }

            // Calculate total
            $price_per_page = $request->print_type == 'color' ? 3 : 0.60;
            $copies = $request->copies;
            $total = $pages * $copies * $price_per_page;
            $paidAmount = $total / 2; // 50%
            $remainingAmount = $total - $paidAmount;

            // Determine user/guest
            $userId = Auth::guard('customer')->id();
            $guestToken = null;
            if (!$userId) {
                $guestToken = $this->getGuestToken();
            }

            // Create print order
            $printOrder = PrintOrder::create([
                'order_number' => $this->generateOrderNumber(),
                'user_id' => $userId,
                'guest_token' => $guestToken,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'file_path' => $filename,
                'paper_size' => $request->paper_size ?? 'A4',
                'print_type' => $request->print_type,
                'copies' => $copies,
                'pages' => $pages,
                'total_amount' => $total,
                'paid_amount' => $paidAmount,
                'remaining_amount' => $remainingAmount,
                'payment_status' => 'partial',
                'payment_mode' => 'online',
            ]);

            // Create Razorpay order
            $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
            $razorpayOrder = $api->order->create([
                'receipt' => 'print_' . $printOrder->id,
                'amount' => $paidAmount * 100, // in paise
                'currency' => 'INR',
                'payment_capture' => 1,
            ]);

            $printOrder->update([
                'razorpay_order_id' => $razorpayOrder->id,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'razorpay_order_id' => $razorpayOrder->id,
                'amount' => $paidAmount * 100,
                'order_id' => $printOrder->id,
                'razorpay_key' => env('RAZORPAY_KEY'),
                'customer_name' => $printOrder->name,
                'customer_email' => $printOrder->email,
                'customer_phone' => $printOrder->phone,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Print payment initiation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error initiating payment: ' . $e->getMessage(),
            ], 500);
        }
    }



    // Handle payment cancel
    public function paymentCancel($order_id)
    {
        $printOrder = PrintOrder::find($order_id);
        if ($printOrder && $printOrder->payment_status == 'pending') {
            $printOrder->update(['payment_status' => 'failed']);
        }
        return redirect()->route('print.upload')->with('error', 'Payment was cancelled.');
    }

    // Order confirmation page


    private function getGuestToken()
    {
        $guestToken = Cookie::get('guest_token');
        if (!$guestToken) {
            $guestToken = Str::random(32);
            Cookie::queue('guest_token', $guestToken, 60 * 24 * 365 * 10);
        }
        return $guestToken;
    }


    public function paymentSuccess(Request $request, $order_id)
    {
        $printOrder = PrintOrder::findOrFail($order_id);

        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        $api->utility->verifyPaymentSignature([
            'razorpay_order_id' => $request->razorpay_order_id,
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'razorpay_signature' => $request->razorpay_signature,
        ]);


        dispatch(new SendOrderMailJob($printOrder));

        return redirect()->route('print.order.confirmation', $printOrder->order_number);
    }
    public function confirmation($order_number)
    {
        $printOrder = PrintOrder::where('order_number', $order_number)
            ->where(function ($q) {
                $q->where('user_id', auth('customer')->id())
                  ->orWhere('guest_token', Cookie::get('guest_token'));
            })
            ->firstOrFail();

        $navcategories = Category::with(['subcategories.childSubcategories'])->where('status', 1)->get();
        $linkss = Link::where('status', 1)->get();

        return view('print-order-confirmation', compact('printOrder', 'navcategories', 'linkss'));
    }

    public function payRemaining($order_number)
    {
        $order = PrintOrder::where('order_number', $order_number)->firstOrFail();

        if ($order->remaining_amount <= 0) {
            return redirect()->route('print.order.confirmation', $order->order_number)
                ->with('success', 'Already Paid');
        }
        $navcategories = Category::with(['subcategories.childSubcategories'])->where('status', 1)->get();
        $linkss = Link::where('status', 1)->get();

        return view('remaining-payment', compact('order', 'navcategories', 'linkss'));
    }
    // public function createRemainingOrder($order_number)
    // {
    //     $order = PrintOrder::where('order_number', $order_number)->firstOrFail();

    //     $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

    //     $razorpayOrder = $api->order->create([
    //         'receipt' => 'remaining_' . $order->id,
    //         'amount' => $order->remaining_amount * 100,
    //         'currency' => 'INR',
    //         'payment_capture' => 1
    //     ]);

    //     return response()->json([
    //         'order_id' => $razorpayOrder->id,
    //         'amount' => $order->remaining_amount * 100,
    //         'key' => env('RAZORPAY_KEY')
    //     ]);
    // }
    public function createRemainingOrder($order_number)
    {
        $order = PrintOrder::where('order_number', $order_number)->firstOrFail();

        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        $razorpayOrder = $api->order->create([
            'receipt' => 'remaining_' . $order->id,
            'amount' => $order->remaining_amount * 100,
            'currency' => 'INR',
            'payment_capture' => 1,
        ]);

        // ✅ VERY IMPORTANT (missing in your code)
        $order->update([
            'razorpay_order_id' => $razorpayOrder->id,
        ]);

        return response()->json([
            'order_id' => $razorpayOrder->id,
            'amount' => $order->remaining_amount * 100,
            'key' => env('RAZORPAY_KEY'),
        ]);
    }
    public function remainingPaymentSuccess(Request $request, $order_number)
    {
        $order = PrintOrder::where('order_number', $order_number)->firstOrFail();

        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        $api->utility->verifyPaymentSignature([
            'razorpay_order_id' => $request->razorpay_order_id,
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'razorpay_signature' => $request->razorpay_signature,
        ]);

        $order->update([
            'payment_status' => 'paid',
            'remaining_payment_mode' => 'online',
            'paid_amount' => $order->total_amount,
            'remaining_amount' => 0,
            'remaining_paid_at' => now(),
            'razorpay_payment_id' => $request->razorpay_payment_id,
        ]);

        // ✅ MAIL AGAIN (FINAL PAID)
        //SendOrderMailJob::dispatch($order);
        (new SendOrderMailJob($order))->handle();
        return redirect()->route('print.order.confirmation', $order->order_number)
            ->with('success', 'Payment Completed');
    }

    public function trackOrder(Request $request)
    {
        $request->validate([
            'order_number' => 'required',
        ]);

        $order = PrintOrder::where('order_number', $request->order_number)->first();

        if (!$order) {
            return back()->with('error', 'Invalid Order Number');
        }

        return redirect()->route('print.order.confirmation', $order->order_number);
    }
}
