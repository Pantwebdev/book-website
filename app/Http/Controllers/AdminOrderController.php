<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MasterOrder;
use App\Models\MasterOrderItem;
use App\Models\OrderStatus;
use App\Models\PaymentStatus;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PDF;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        // Check if this is a fresh page load vs AJAX request
        // Store previous URL in session to detect refresh
        $previousUrl = session()->get('previous_orders_url');
        $currentUrl = $request->fullUrl();

        // If this is not an AJAX request and previous URL was same as current,
        // it means user refreshed the page - clear filters
        if (!$request->ajax() && $previousUrl && $previousUrl === $currentUrl) {
            // Check if there are any active filters
            if ($request->hasAny(['order_number', 'customer', 'order_status', 'payment_status',
                'payment_method', 'delivery_status', 'date_from', 'date_to',
                'min_amount', 'max_amount', 'sort'])) {
                // Clear session and redirect without parameters
                session()->forget('previous_orders_url');
                return redirect()->route('admin.orders.index');
            }
        }

        // Store current URL for next request
        session()->put('previous_orders_url', $currentUrl);

        $query = MasterOrder::with(['user', 'items', 'orderStatus', 'paymentStatus', 'paymentMethod']);

        // 🔍 Order ID
        if ($request->filled('order_number')) {
            $query->where('order_number', 'like', '%' . $request->order_number . '%');
        }

        // 👤 Customer (name / mobile / email)
        if ($request->filled('customer')) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->customer . '%')
                  ->orWhere('last_name', 'like', '%' . $request->customer . '%')
                  ->orWhere('email', 'like', '%' . $request->customer . '%')
                  ->orWhere('phone', 'like', '%' . $request->customer . '%');
            });
        }

        // 📦 Order Status (नया तरीका)
        if ($request->filled('order_status')) {
            $query->whereHas('orderStatus', function ($q) use ($request) {
                $q->where('order_status', $request->order_status);
            });
        }

        // 💳 Payment Status (नया तरीका)
        if ($request->filled('payment_status')) {
            $query->whereHas('paymentStatus', function ($q) use ($request) {
                $q->where('payment_status', $request->payment_status);
            });
        }

        // 💰 Payment Method (नया तरीका)
        if ($request->filled('payment_method')) {
            $query->whereHas('paymentMethod', function ($q) use ($request) {
                $q->where('payment_type', $request->payment_method);
            });
        }

        // 🚚 Delivery Status - अब order_status का हिस्सा है
        if ($request->filled('delivery_status')) {
            // Map delivery status to order status
            $deliveryToOrderStatus = [
                'shipped' => 'Shipped',
                'delivered' => 'Delivered',
                'out_for_delivery' => 'Out for Delivery',
            ];

            if (isset($deliveryToOrderStatus[$request->delivery_status])) {
                $query->whereHas('orderStatus', function ($q) use ($request, $deliveryToOrderStatus) {
                    $q->where('order_status', $deliveryToOrderStatus[$request->delivery_status]);
                });
            }
        }

        // 📅 Date Range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // 💵 Amount
        if ($request->filled('min_amount')) {
            $query->where('grand_total', '>=', $request->min_amount);
        }
        if ($request->filled('max_amount')) {
            $query->where('grand_total', '<=', $request->max_amount);
        }

        // Sorting
        $sort = $request->input('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'total_high':
                $query->orderBy('grand_total', 'desc');
                break;
            case 'total_low':
                $query->orderBy('grand_total', 'asc');
                break;
            case 'a-z':
                $query->orderBy('first_name', 'asc');
                break;
            case 'z-a':
                $query->orderBy('first_name', 'desc');
                break;
            default:
                $query->latest();
        }

        $orders = $query->paginate(20);

        // Get all available statuses for filter dropdowns
        $orderStatuses = OrderStatus::where('status', 1)->get();
        $paymentStatuses = PaymentStatus::all();
        $paymentMethods = PaymentMethod::all();

        // Stats (नया तरीका)
        $totalOrders   = MasterOrder::count();
        $todayOrders   = MasterOrder::whereDate('created_at', today())->count();
        $totalRevenue  = MasterOrder::sum('grand_total');

        // Pending orders - जो 'Pending' या 'Order Placed' status में हैं
        $pendingStatus = OrderStatus::where('order_status', 'Pending')
                         ->orWhere('order_status', 'Order Placed')
                         ->orWhere('order_status', 'Processing')
                         ->pluck('id');

        $pendingOrders = MasterOrder::whereIn('order_status_id', $pendingStatus)->count();

        // AJAX response (ONLY table)
        if ($request->ajax()) {
            // For AJAX requests, also pass stats to update the cards
            $statsHtml = view('admin.orders.partials.stats_cards', compact(
                'totalOrders',
                'todayOrders',
                'totalRevenue',
                'pendingOrders',
            ))->render();

            $tableHtml = view('admin.orders.partials.orders_table', compact('orders'))->render();

            return response()->json([
                'table' => $tableHtml,
                'stats' => $statsHtml,
                'orderStats' => "Showing {$orders->firstItem()} to {$orders->lastItem()} of {$orders->total()} entries",
            ]);
        }

        return view('admin.orders.index', compact(
            'orders',
            'totalOrders',
            'todayOrders',
            'totalRevenue',
            'pendingOrders',
            'orderStatuses',
            'paymentStatuses',
            'paymentMethods',
        ));
    }

    public function show($id)
    {
        $order = MasterOrder::with(['items', 'user', 'orderStatus', 'paymentStatus', 'paymentMethod'])->findOrFail($id);

        // Get all available statuses
        $orderStatuses = OrderStatus::where('status', 1)->get();
        $paymentStatuses = PaymentStatus::all();

        return view('admin.orders.show', compact('order', 'orderStatuses', 'paymentStatuses'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'order_status' => 'required|string',
        ]);

        $order = MasterOrder::findOrFail($id);

        // Find the order status by name
        $orderStatus = OrderStatus::where('order_status', $request->order_status)->first();

        if (!$orderStatus) {
            return redirect()->back()->with('error', 'Invalid order status.');
        }

        $oldStatus = $order->orderStatus ? $order->orderStatus->order_status : 'N/A';

        // Update using the model method
        if ($order->updateOrderStatus($request->order_status)) {
            Log::info("Order status changed from {$oldStatus} to {$request->order_status}", [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'user_id' => auth()->id(),
                'action' => 'order_status_update',
            ]);

            // Send email notification to customer if status is delivered or shipped
            if (in_array($request->order_status, ['Shipped', 'Delivered'])) {
                $this->sendStatusUpdateEmail($order, $request->order_status);
            }

            return redirect()->back()->with('success', 'Order status updated successfully!');
        }

        return redirect()->back()->with('error', 'Failed to update order status.');
    }

    public function updatePaymentStatus(Request $request, $id)
    {
        $request->validate([
            'payment_status' => 'required|string',
        ]);

        $order = MasterOrder::findOrFail($id);

        // Find the payment status by name
        $paymentStatus = PaymentStatus::where('payment_status', $request->payment_status)->first();

        if (!$paymentStatus) {
            return redirect()->back()->with('error', 'Invalid payment status.');
        }

        $oldStatus = $order->paymentStatus ? $order->paymentStatus->payment_status : 'N/A';

        // Update using the model method
        if ($order->updatePaymentStatus($request->payment_status)) {
            Log::info("Payment status changed from {$oldStatus} to {$request->payment_status}", [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'user_id' => auth()->id(),
                'action' => 'payment_status_update',
            ]);

            return redirect()->back()->with('success', 'Payment status updated successfully!');
        }

        return redirect()->back()->with('error', 'Failed to update payment status.');
    }

    public function destroy($id)
    {
        $order = MasterOrder::findOrFail($id);

        // Delete order items first
        $order->items()->delete();

        // Delete the order
        $order->delete();

        Log::info("Order deleted", [
            'order_id' => $id,
            'order_number' => $order->order_number,
            'user_id' => auth()->id(),
            'action' => 'order_delete',
        ]);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order deleted successfully!');
    }

    public function invoice($id)
    {
        $order = MasterOrder::with(['items', 'orderStatus', 'paymentStatus', 'paymentMethod'])->findOrFail($id);

        $orderStatuses = OrderStatus::where('status', 1)->get();
        $paymentStatuses = PaymentStatus::all();

        $orderStatus = $order->orderStatus ? $order->orderStatus->order_status : 'Pending';
        $paymentStatus = $order->paymentStatus ? $order->paymentStatus->payment_status : 'Pending';
        $paymentMethod = $order->paymentMethod ? $order->paymentMethod->description : 'Unknown';
        $itemsWithImages = [];
        foreach ($order->items as $item) {
            $itemData = $item->toArray();

            // Convert image to base64
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
        $setting = Setting::first();
        $data = [
            'order' => $order,
            'items' => $itemsWithImages,
            'orderStatuses' => $orderStatuses,
            'paymentStatuses' => $paymentStatuses,
            'currentStatus' => $order->currentOrderStatus,
            'orderStatus' => $orderStatus,
            'paymentStatus' => $paymentStatus,
            'paymentMethod' => $paymentMethod,
            'company' => [
                'name' => env('APP_NAME', 'Ajhuie Book Store'),
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

        $html = view('pdf.admin-invoice', $data)->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->stream('invoice-admin-' . $order->order_number . '.pdf');
    }

    public function export(Request $request)
    {
        $query = MasterOrder::with(['orderStatus', 'paymentStatus', 'paymentMethod']);

        // Apply all filters
        if ($request->filled('order_number')) {
            $query->where('order_number', 'like', '%' . $request->order_number . '%');
        }

        if ($request->filled('customer')) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->customer . '%')
                  ->orWhere('last_name', 'like', '%' . $request->customer . '%')
                  ->orWhere('email', 'like', '%' . $request->customer . '%')
                  ->orWhere('phone', 'like', '%' . $request->customer . '%');
            });
        }

        // Order Status Filter
        if ($request->filled('order_status') && $request->order_status !== 'all') {
            $query->whereHas('orderStatus', function ($q) use ($request) {
                $q->where('order_status', $request->order_status);
            });
        }

        // Payment Status Filter
        if ($request->filled('payment_status') && $request->payment_status !== 'all') {
            $query->whereHas('paymentStatus', function ($q) use ($request) {
                $q->where('payment_status', $request->payment_status);
            });
        }

        // Payment Method Filter
        if ($request->filled('payment_method') && $request->payment_method !== 'all') {
            $query->whereHas('paymentMethod', function ($q) use ($request) {
                $q->where('payment_type', $request->payment_method);
            });
        }

        // Date filters
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Amount filters
        if ($request->filled('min_amount')) {
            $query->where('grand_total', '>=', $request->min_amount);
        }

        if ($request->filled('max_amount')) {
            $query->where('grand_total', '<=', $request->max_amount);
        }

        // Apply sort
        $sort = $request->input('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'total_high':
                $query->orderBy('grand_total', 'desc');
                break;
            case 'total_low':
                $query->orderBy('grand_total', 'asc');
                break;
            case 'a-z':
                $query->orderBy('first_name', 'asc');
                break;
            case 'z-a':
                $query->orderBy('first_name', 'desc');
                break;
            default:
                $query->latest();
        }

        $orders = $query->get();

        $fileName = 'orders_export_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');

            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");

            // Headers
            fputcsv($file, [
                'Order Number',
                'Customer Name',
                'Email',
                'Phone',
                'Order Date',
                'Payment Method',
                'Order Status',
                'Payment Status',
                'Subtotal',
                'Tax',
                'Shipping',
                'Grand Total',
                'Address',
                'City',
                'State',
                'Pincode',
                'Country',
                'Items Count',
                'Payment Collected At',
                'Shipped At',
                'Delivered At',
            ]);

            // Data
            foreach ($orders as $order) {
                // Get current status
                $currentStatus = $order->currentOrderStatus;

                fputcsv($file, [
                    $order->order_number,
                    $order->first_name . ' ' . $order->last_name,
                    $order->email,
                    $order->phone,
                    $order->created_at->format('Y-m-d H:i:s'),
                    $order->paymentMethod ? $order->paymentMethod->payment_type : 'Unknown',
                    $currentStatus['label'],
                    $currentStatus['payment_label'],
                    $order->subtotal,
                    $order->tax_amount,
                    $order->shipping_charge ?? 0,
                    $order->grand_total,
                    $order->address,
                    $order->city,
                    $order->state,
                    $order->pincode,
                    $order->country,
                    $order->items()->count(),
                    $order->payment_collected_at ? $order->payment_collected_at->format('Y-m-d H:i:s') : '',
                    $order->shipped_at ? $order->shipped_at->format('Y-m-d H:i:s') : '',
                    $order->delivered_at ? $order->delivered_at->format('Y-m-d H:i:s') : '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function sendStatusUpdateEmail($order, $status)
    {
        try {
            // You'll need to create this email template
            // Mail::to($order->email)->send(new OrderStatusUpdateMail($order, $status));
            \Log::info("Order status update email sent for order: " . $order->order_number . " Status: " . $status);
        } catch (\Exception $e) {
            \Log::error('Status update email error: ' . $e->getMessage());
        }
    }
}
