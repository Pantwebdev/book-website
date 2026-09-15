@extends('customer.layouts.app')

@section('content')
<div class="container py-4">

    <h4 class="mb-3">My Orders</h4>

    <div class="row mb-4">
        <div class="col-md-6">
            <strong>Total Orders:</strong> {{ $totalOrders }}
        </div>
        <div class="col-md-6">
            <strong>Total Spent:</strong> ₹{{ number_format($totalSpent, 2) }}
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="ordersTable">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Order No</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Payment</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $index => $order)
                    <tr>
                        <td>{{ $orders->firstItem() + $index }}</td>
                        <td>{{ $order->order_number }}</td>
                        <td>{{ $order->created_at->format('d M Y') }}</td>
                        <td>
                            <span class="badge bg-info">
                                {{ $order->order_status_label }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-secondary">
                                {{ $order->payment_status_label }}
                            </span>
                        </td>
                        <td>{{ $order->formatted_grand_total }}</td>
                        <td>
                            <a href="{{ route('customer.order.details', $order->order_number) }}"
                               class="btn btn-sm btn-primary">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">
                            No orders found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-between align-items-center px-3 py-3 border-top">
            <small class="text-muted">
                Showing {{ $orders->firstItem() }}–{{ $orders->lastItem() }}
                of {{ $orders->total() }} orders
            </small>

            <div class="pagination-wrapper">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        </div>


</div>
@endsection
