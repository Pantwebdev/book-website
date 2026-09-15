@extends('customer.layouts.app')

@section('content')
<div class="container-fluid py-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">
            Order Details
            <small class="text-muted">#{{ $order->order_number }}</small>
        </h4>

        <a href="{{ route('customer.orders') }}" class="btn btn-sm btn-outline-secondary">
            ← Back to Orders
        </a>
    </div>

    <!-- ORDER SUMMARY -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Order Info</h6>
                    <p class="mb-1"><strong>Date:</strong> {{ $order->created_at->format('d M Y') }}</p>
                    <p class="mb-1"><strong>Status:</strong>
                        <span class="badge bg-info">
                            {{ $order->order_status_label }}
                        </span>
                    </p>
                    <p class="mb-0"><strong>Payment:</strong>
                        <span class="badge bg-secondary">
                            {{ $order->payment_status_label }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Customer</h6>
                    <p class="mb-1">{{ $order->full_name }}</p>
                    <p class="mb-1">{{ $order->email }}</p>
                    <p class="mb-0">{{ $order->phone }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Shipping Address</h6>
                    <p class="mb-0">
                        {{ $order->shipping_address ?? $order->address }}<br>
                        {{ $order->shipping_city ?? $order->city }},
                        {{ $order->shipping_state ?? $order->state }}<br>
                        {{ $order->shipping_pincode ?? $order->pincode }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- ORDER ITEMS -->
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h6 class="mb-0">Ordered Items</h6>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('userassets/image/product/' . $item->image) }}"
                                         width="45" class="me-2 rounded">
                                    <div>
                                        <div>{{ $item->product_name ?? 'Product' }}</div>
                                        <small class="text-muted">
                                            {{ $item->color }} | {{ $item->size }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>₹{{ number_format($item->price, 2) }}</td>
                            <td>{{ $item->qty }}</td>
                            <td>₹{{ number_format($item->price * $item->qty, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- TOTALS -->
        <div class="card-body border-top">
            <div class="row justify-content-end">
                <div class="col-md-4">
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between">
                            <span>Subtotal</span>
                            <span>₹{{ number_format($order->subtotal, 2) }}</span>
                        </li>
                        <li class="d-flex justify-content-between">
                            <span>Tax</span>
                            <span>₹{{ number_format($order->tax_amount, 2) }}</span>
                        </li>
                        <li class="d-flex justify-content-between">
                            <span>Shipping</span>
                            <span>₹{{ number_format($order->shipping_charge, 2) }}</span>
                        </li>
                        <li class="d-flex justify-content-between fw-bold border-top pt-2 mt-2">
                            <span>Grand Total</span>
                            <span>₹{{ number_format($order->grand_total, 2) }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
