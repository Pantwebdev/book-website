<!-- Orders Table Container -->
<div id="ordersTableContainer">
    <div class="table-responsive">
        <table id="ordersTable" class="display table table-striped table-hover">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Payment</th>
                    <th>Total</th>
                    <th>Items</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                @php
                    $currentStatus = $order->currentOrderStatus;
                    
                    // Define status colors
                    $statusColors = [
                        'Pending' => 'warning',
                        'Order Placed' => 'info',
                        'Processing' => 'primary',
                        'Shipped' => 'success',
                        'Delivered' => 'dark',
                        'Cancelled' => 'danger',
                        'Refunded' => 'secondary',
                        'Confirmed' => 'info'
                    ];
                    
                    $color = $statusColors[$currentStatus['label']] ?? 'secondary';
                    
                    // Payment status colors
                    $paymentColors = [
                        'Pending' => 'warning',
                        'Success' => 'success',
                        'Failed' => 'danger',
                        'Refunded' => 'info',
                        'Initiated' => 'primary'
                    ];
                    $paymentColor = $paymentColors[$currentStatus['payment_label']] ?? 'secondary';
                @endphp
                <tr>
                    <td>
                        <strong>{{ $order->order_number }}</strong><br>
                        <small class="text-muted">
                            {{ $order->paymentMethod ? $order->paymentMethod->payment_type : 'Unknown' }}
                        </small>
                    </td>
                    <td>
                        <strong>{{ $order->first_name }} {{ $order->last_name }}</strong><br>
                        <small>{{ $order->email }}</small><br>
                        <small>{{ $order->phone }}</small>
                    </td>
                    <td>
                        {{ $order->created_at->format('d M Y') }}<br>
                        <small class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                    </td>
                    <td>
                        <span class="badge badge-{{ $color }}">
                            {{ $currentStatus['label'] }}
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-{{ $paymentColor }}">
                            {{ $currentStatus['payment_label'] }}
                        </span>
                    </td>
                    <td>
                        <strong>₹{{ number_format($order->grand_total, 2) }}</strong><br>
                        @if($order->discount_amount > 0 || $order->coupon_discount > 0)
                        <small class="text-success">
                            Saved ₹{{ number_format($order->discount_amount + $order->coupon_discount, 2) }}
                        </small>
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-info">{{ $order->items->count() }}</span>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.orders.show', $order->id) }}" 
                               class="btn btn-sm btn-primary" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.orders.invoice', $order->id) }}" 
                               class="btn btn-sm btn-secondary" title="Invoice" target="_blank">
                                <i class="fas fa-file-invoice"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-danger delete-order" 
                                    data-id="{{ $order->id }}" 
                                    data-number="{{ $order->order_number }}"
                                    title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
                
                @if($orders->count() == 0)
                <tr>
                    <td colspan="8" class="text-center py-4">
                        <div class="text-muted">
                            <i class="fas fa-box-open fa-2x mb-2"></i><br>
                            No orders found matching your criteria.
                        </div>
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination Container -->
<!-- <div id="paginationContainer">
    @if($orders->lastPage() > 1)
    <div class="d-flex justify-content-between align-items-center mt-3">
        <div>
            {{ $orders->links('pagination::bootstrap-4') }}
        </div>
    </div>
    @endif
</div> -->