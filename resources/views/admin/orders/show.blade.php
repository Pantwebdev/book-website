@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">
                <i class="fas fa-eye"></i>
                Order Details
            </h4>
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="{{ route('dashboard') }}">
                        <i class="flaticon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="flaticon-right-arrow"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.orders.index') }}">Orders</a>
                </li>
                <li class="separator">
                    <i class="flaticon-right-arrow"></i>
                </li>
                <li class="nav-item">
                    <span>{{ $order->order_number }}</span>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-8">
               <!-- Order Status Card -->
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <h4 class="card-title">Order Status & Actions</h4>
            <div class="ml-auto">
                @php
                    $currentStatus = $order->currentOrderStatus;
                    $statusColors = [
                        'Pending' => 'warning',
                        'Order Placed' => 'warning',
                        'Confirmed' => 'info',
                        'Processing' => 'primary',
                        'Shipped' => 'success',
                        'Delivered' => 'dark',
                        'Cancelled' => 'danger',
                        'Refunded' => 'danger'
                    ];
                    $color = $statusColors[$currentStatus['label']] ?? 'secondary';
                    $paymentColors = [
                        'Pending' => 'warning',
                        'Initiated' => 'info',
                        'Success' => 'success',
                        'Failed' => 'danger',
                        'Refunded' => 'danger'
                    ];
                    $paymentColor = $paymentColors[$currentStatus['payment_label']] ?? 'secondary';
                @endphp
                <span class="badge badge-{{ $color }}">
                    {{ $currentStatus['label'] }}
                </span>
                <span class="badge badge-{{ $paymentColor }} ml-1">
                    {{ $currentStatus['payment_label'] }}
                </span>
            </div>
        </div>
    </div>
    <div class="card-body">
        <!-- Status History -->
       

        <div class="row">
            <div class="col-md-6">
                <form method="POST" action="{{ route('admin.orders.update-status', $order->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label><strong>Update Order Status</strong></label>
                        <select name="order_status" class="form-control">
                            @foreach($orderStatuses as $status)
                            <option value="{{ $status->order_status }}" 
                                    {{ $order->orderStatus && $order->orderStatus->id == $status->id ? 'selected' : '' }}>
                                {{ $status->order_status }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-sync-alt"></i> Update Order Status
                    </button>
                </form>
            </div>
            <div class="col-md-6">
                <form method="POST" action="{{ route('admin.orders.update-payment-status', $order->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label><strong>Update Payment Status</strong></label>
                        <select name="payment_status" class="form-control">
                            @foreach($paymentStatuses as $status)
                            <option value="{{ $status->payment_status }}" 
                                    {{ $order->paymentStatus && $order->paymentStatus->id == $status->id ? 'selected' : '' }}>
                                {{ $status->payment_status }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-money-check-alt"></i> Update Payment Status
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

                <!-- Order Items Card -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Order Items ({{ $order->items->count() }})</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th class="text-center">Price</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-center">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ url('userassets/image/product/' . ($item->image ?? 'placeholder.png')) }}" 
                                                         alt="{{ $item->product_name }}" 
                                                         style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="mb-1">{{ $item->product_name }}</h6>
                                                    <div class="text-muted small">
                                                        SKU: {{ $item->sku }}<br>
                                                        @if($item->color)
                                                        Color: {{ $item->color }} 
                                                        @endif
                                                        @if($item->size)
                                                        | Size: {{ $item->size }}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="fw-bold">₹{{ number_format($item->price, 2) }}</div>
                                            @if($item->mrp_price > $item->price)
                                            <div class="text-muted text-decoration-line-through small">
                                                ₹{{ number_format($item->mrp_price, 2) }}
                                            </div>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            <span class="badge badge-info">{{ $item->qty }}</span>
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="fw-bold text-primary">₹{{ number_format($item->item_total, 2) }}</div>
                                            @if($item->mrp_price > $item->price)
                                            <div class="text-success small">
                                                Save ₹{{ number_format($item->item_mrp_total - $item->item_total, 2) }}
                                            </div>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Order Summary Card -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Order Summary</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Order Number:</strong></td>
                                        <td>{{ $order->order_number }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Order Date:</strong></td>
                                        <td>{{ $order->created_at->format('d M, Y h:i A') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Payment Method:</strong></td>
                                       <td>
                            {{-- नया तरीका - paymentMethod relationship से --}}
                            @if($order->paymentMethod)
                                {{ strtoupper($order->paymentMethod->payment_type) }}
                            @else
                                {{-- Fallback for old data --}}
                                {{ strtoupper($order->payment_method) }}
                            @endif
                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Payment Status:</strong></td>
                                        <td>
                            @php
                                // Payment status color mapping
                                $paymentStatus = $order->paymentStatus;
                                $paymentLabel = $paymentStatus ? $paymentStatus->payment_status : 'Unknown';
                                $paymentColorMap = [
                                    'Success' => 'success',
                                    'Collected' => 'success',
                                    'Pending' => 'warning',
                                    'Initiated' => 'info',
                                    'Failed' => 'danger',
                                    'Refunded' => 'danger'
                                ];
                                $paymentColor = $paymentColorMap[$paymentLabel] ?? 'secondary';
                            @endphp
                            <span class="badge badge-{{ $paymentColor }}">
                                {{ $paymentLabel }}
                            </span>
                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Order Status:</strong></td>
                                        <td>
                            @php
                                // Order status color mapping
                                $orderStatus = $order->orderStatus;
                                $orderLabel = $orderStatus ? $orderStatus->order_status : 'Unknown';
                                $orderColorMap = [
                                    'Pending' => 'warning',
                                    'Order Placed' => 'warning',
                                    'Confirmed' => 'info',
                                    'Processing' => 'primary',
                                    'Shipped' => 'success',
                                    'Delivered' => 'dark',
                                    'Cancelled' => 'danger',
                                    'Refunded' => 'danger'
                                ];
                                $orderColor = $orderColorMap[$orderLabel] ?? 'secondary';
                            @endphp
                            <span class="badge badge-{{ $orderColor }}">
                                {{ $orderLabel }}
                            </span>
                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Subtotal:</strong></td>
                                        <td class="text-right">₹{{ number_format($order->subtotal, 2) }}</td>
                                    </tr>
                                    @if($order->discount_amount > 0)
                                    <tr>
                                        <td><strong>Product Discount:</strong></td>
                                        <td class="text-right text-success">-₹{{ number_format($order->discount_amount, 2) }}</td>
                                    </tr>
                                    @endif
                                    @if($order->coupon_discount > 0)
                                    <tr>
                                        <td><strong>Coupon Discount:</strong></td>
                                        <td class="text-right text-success">-₹{{ number_format($order->coupon_discount, 2) }}</td>
                                    </tr>
                                    @endif
                                    @if($order->tax_amount > 0)
                                    <tr>
                                        <td><strong>Tax Amount:</strong></td>
                                        <td class="text-right">₹{{ number_format($order->tax_amount, 2) }}</td>
                                    </tr>
                                    @endif
                                    @if($order->shipping_charge > 0)
                                    <tr>
                                        <td><strong>Shipping Charge:</strong></td>
                                        <td class="text-right">₹{{ number_format($order->shipping_charge, 2) }}</td>
                                    </tr>
                                    @else
                                    <tr>
                                        <td><strong>Shipping Charge:</strong></td>
                                        <td class="text-right text-success">FREE</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td><strong class="text-primary">Grand Total:</strong></td>
                                        <td class="text-right text-primary fw-bold">₹{{ number_format($order->grand_total, 2) }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Customer Information Card -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Customer Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="customer-info">
                            <div class="mb-3">
                                <strong>Name:</strong><br>
                                {{ $order->first_name }} {{ $order->last_name }}
                            </div>
                            <div class="mb-3">
                                <strong>Email:</strong><br>
                                {{ $order->email }}
                            </div>
                            <div class="mb-3">
                                <strong>Phone:</strong><br>
                                {{ $order->phone }}
                            </div>
                            
                        </div>
                    </div>
                </div>

                <!-- Shipping Address Card -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Shipping Address</h4>
                    </div>
                    <div class="card-body">
                        <address>
                            <strong>{{ $order->first_name }} {{ $order->last_name }}</strong><br>
                            {{ $order->address }}<br>
                            @if($order->address2)
                            {{ $order->address2 }}<br>
                            @endif
                            {{ $order->city }}, {{ $order->state }}<br>
                            {{ $order->pincode }}, {{ $order->country }}<br>
                            <strong>Phone:</strong> {{ $order->phone }}
                        </address>
                        
                        @if($order->shipping_address)
                        <hr>
                        <h6>Different Shipping Address:</h6>
                        <address>
                            <strong>{{ $order->shipping_first_name }} {{ $order->shipping_last_name }}</strong><br>
                            {{ $order->shipping_address }}<br>
                            {{ $order->shipping_city }}, {{ $order->shipping_state }}<br>
                            {{ $order->shipping_pincode }}<br>
                            <strong>Phone:</strong> {{ $order->shipping_phone }}
                        </address>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons Card -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Quick Actions</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.orders.invoice', $order->id) }}" 
                               class="btn btn-secondary" target="_blank">
                                <i class="fas fa-file-invoice"></i> Download Invoice
                            </a>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-info">
                                <i class="fas fa-arrow-left"></i> Back to Orders
                            </a>
                            <button type="button" class="btn btn-danger delete-order" 
                                    data-id="{{ $order->id }}"
                                    data-number="{{ $order->order_number }}">
                                <i class="fas fa-trash"></i> Delete Order
                            </button>
                        </div>
                        
                        <hr>
                        
                        <div class="text-center">
                            <small class="text-muted">Order Created: {{ $order->created_at->diffForHumans() }}</small><br>
                            <small class="text-muted">Last Updated: {{ $order->updated_at->diffForHumans() }}</small>
                        </div>
                    </div>
                </div>

                <!-- Order Notes Card -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Order Notes</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="#" id="notesForm">
                            @csrf
                            <div class="form-group">
                                <textarea class="form-control" name="admin_notes" id="admin_notes" rows="3" 
                                          placeholder="Add internal notes about this order...">{{ $order->admin_notes ?? '' }}</textarea>
                            </div>
                            <button type="button" class="btn btn-sm btn-primary" id="saveNoteBtn">
                                <i class="fas fa-save"></i> Save Note
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete order <strong id="deleteOrderNumber"></strong>? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Order</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Delete order confirmation
    $('.delete-order').click(function() {
        const orderId = $(this).data('id');
        const orderNumber = $(this).data('number');
        
        $('#deleteOrderNumber').text(orderNumber);
        $('#deleteForm').attr('action', '/admin/orders/' + orderId);
        $('#deleteModal').modal('show');
    });
    
    // Save note
    $('#saveNoteBtn').click(function() {
        const notes = $('#admin_notes').val();
        const orderId = '{{ $order->id }}';
        
        $.ajax({
            url: '/admin/orders/' + orderId + '/save-note',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                admin_notes: notes
            },
            success: function(response) {
                toastr.success('Note saved successfully!');
            },
            error: function(xhr) {
                toastr.error('Error saving note');
            }
        });
    });
    
    // Toastr notifications
    @if(session('success'))
    toastr.success('{{ session('success') }}');
    @endif
    
    @if(session('error'))
    toastr.error('{{ session('error') }}');
    @endif
});
</script>

<style>
.card {
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
}

.badge {
    font-size: 0.75em;
    padding: 0.4em 0.8em;
}

.table th {
    font-weight: 600;
}

.customer-info {
    font-size: 0.9rem;
}

address {
    font-style: normal;
    line-height: 1.6;
}

.btn-group-vertical .btn {
    margin-bottom: 10px;
}

.btn-group-vertical .btn:last-child {
    margin-bottom: 0;
}
</style>
@endsection