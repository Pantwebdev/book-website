@include('userheader')

<div class="product_listbanner">
    <img src="{{url('userassets/image/banner-list.png')}}" alt="">
</div>

<section class="py-5">
    <div class="container">
        <!-- Success Message -->
        <div class="row justify-content-center mb-4">
            <div class="col-lg-10">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" 
                     style="border-radius: 15px; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="bi bi-check-circle-fill fs-1"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="alert-heading mb-1">🎉 Order Placed Successfully!</h4>
                            <p class="mb-0">{{ session('success') }}</p>
                            <p class="mb-0 small text-muted mt-1">Order confirmation email has been sent to {{ $masterOrder->email }}</p>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Order Status Progress -->
      
        <div class="row mb-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-truck me-2"></i>Order Journey
                            </h5>
                            <div class="ms-auto">
                                @php
                                    // Status colors mapping
                                    $statusColors = [
                                        'Pending' => 'warning',
                                        'Confirmed' => 'info',
                                        'Processing' => 'primary',
                                        'Shipped' => 'success',
                                        'Delivered' => 'dark',
                                        'Cancelled' => 'danger',
                                        'Refunded' => 'secondary'
                                    ];
                                    
                                    $orderStatus = $masterOrder->orderStatus ? $masterOrder->orderStatus->order_status : 'Pending';
                                    $paymentStatus = $masterOrder->paymentStatus ? $masterOrder->paymentStatus->payment_status : 'Pending';
                                    
                                    $color = $statusColors[$orderStatus] ?? 'secondary';
                                    $paymentColor = in_array($paymentStatus, ['Success', 'Collected', 'Paid']) ? 'success' : 
                                                ($paymentStatus == 'Failed' ? 'danger' : 'warning');
                                @endphp
                               
                            </div>
                        </div>
                        
                        <!-- Dynamic Status Timeline -->
                        <!-- Dynamic Status Timeline -->
<div class="order-timeline">
    @php
        // Get only active statuses (status = 1)
        $activeStatuses = \App\Models\OrderStatus::where('status', 1)->get();
        
        $currentStatus = $masterOrder->orderStatus->order_status ?? 'Order Placed';
        
        // Status icons mapping
        $statusIcons = [
            'Order Placed' => 'bi-cart',
            'Processing' => 'bi-gear',
            'Shipped' => 'bi-truck',
            'Out for Delivery' => 'bi-truck-flatbed',
            'Delivered' => 'bi-house',
            'Cancelled' => 'bi-x',
            'Returned' => 'bi-arrow-return-left',
            'Refunded' => 'bi-currency-exchange',
        ];
        
        // Get status dates
        $statusDates = [
            'Order Placed' => $masterOrder->created_at,
            'Shipped' => $masterOrder->shipped_at,
            'Delivered' => $masterOrder->delivered_at,
            'Cancelled' => $masterOrder->cancelled_at,
            'Refunded' => $masterOrder->refunded_at,
        ];
        
        // Get all status names in order
        $statusNames = $activeStatuses->pluck('order_status')->toArray();
        $currentIndex = array_search($currentStatus, $statusNames);
    @endphp
    
    <div class="status-bar">
        @foreach($activeStatuses as $status)
            @php
                $statusName = $status->order_status;
                $icon = $statusIcons[$statusName] ?? 'bi-circle';
                $date = $statusDates[$statusName] ?? null;
                $checkIndex = array_search($statusName, $statusNames);
                $done = $checkIndex !== false && $currentIndex !== false && $checkIndex <= $currentIndex;
            @endphp
            
            <div class="status-step {{ $done ? 'done' : '' }}">
                <div class="icon">
                    <i class="bi {{ $icon }}"></i>
                </div>
                
                <strong>{{ $statusName }}</strong>
                
               @if($done)
    @if($date)
        <p class="text-muted">
            {{ \Carbon\Carbon::parse($date)->format('d M Y, h:i A') }}
        </p>
    @else
        <p class="text-muted">
            {{ $masterOrder->updated_at->format('d M Y, h:i A') }}
        </p>
    @endif
@else
    <p class="text-muted">Pending</p>
@endif

            </div>
        @endforeach
    </div>
</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Order Summary Card -->
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                        <div>
                            <h5 class="card-title mb-0">
                                <i class="bi bi-receipt me-2"></i>Order Summary
                            </h5>
                            <p class="text-muted small mb-0 mt-1">Order #{{ $masterOrder->order_number }}</p>
                        </div>
                        <div>
                            @if($masterOrder->paymentMethod)
                                @if($masterOrder->paymentMethod->isCOD())
                                    <span class="badge ordercon-cash fs-6 px-3 py-2">
                                        <i class="bi bi-cash-stack me-1"></i>Cash On Delevery
                                    </span>
                                @else
                                    <span class="badge bg-primary fs-6 px-3 py-2">
                                        <i class="bi bi-credit-card me-1"></i>Online
                                    </span>
                                @endif
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Order Items -->
                        <div class="order-items-table mb-4">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-3">Product</th>
                                            <th class="text-center">Price</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-end pe-3">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($masterOrder->items as $item)
                                        <tr class="border-bottom">
                                            <td class="ps-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <img src="{{ url('userassets/image/product/' . ($item->image ?? 'placeholder.png')) }}" 
                                                             alt="{{ $item->product_name }}" 
                                                             class="rounded" 
                                                             style="width: 60px; height: 60px; object-fit: cover;object-position: top;">
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h6 class="mb-1">{{ $item->product_name }}</h6>
                                                        <div class="d-flex flex-wrap gap-1">
                                                            @if($item->color)
                                                            <span class="badge bg-light text-dark small">
                                                                <i class="bi bi-palette me-1"></i>{{ $item->color }}
                                                            </span>
                                                            @endif
                                                            @if($item->size)
                                                            <span class="badge bg-light text-dark small">
                                                                <i class="bi bi-rulers me-1"></i>{{ $item->size }}
                                                            </span>
                                                            @endif
                                                            <span class="badge bg-light text-dark small">
                                                                ISBN: {{ $item->sku }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center align-middle">
                                                <div>₹{{ number_format($item->price, 2) }}</div>
                                                @if($item->mrp_price > $item->price)
                                                <small class="text-muted text-decoration-line-through d-block">
                                                    ₹{{ number_format($item->mrp_price, 2) }}
                                                </small>
                                                @endif
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="badge bg-light text-dark fs-6 px-3 py-2">
                                                    {{ $item->qty }}
                                                </span>
                                            </td>
                                            <td class="text-end pe-3 align-middle">
                                                <div class="fw-bold order-con-price">₹{{ number_format($item->item_total, 2) }}</div>
                                                @if($item->mrp_price > $item->price)
                                                <small class="text-success small">
                                                    Save ₹{{ number_format($item->item_mrp_total - $item->item_total, 2) }}
                                                </small>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Price Breakdown -->
                        <div class="price-breakdown-card border rounded p-4">
                            <h6 class="mb-3 text-primary">
                                <i class="bi bi-calculator me-2"></i>Price Breakdown
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span>Total MRP</span>
                                            <span>₹{{ number_format($masterOrder->total_mrp, 2) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-1 ">
                                            <span>Discount on MRP</span>
                                            <span class="text-success">-₹{{ number_format($masterOrder->discount_amount, 2) }}</span>
                                        </div>
                                        @if($masterOrder->coupon_discount > 0)
                                        <div class="d-flex justify-content-between mb-1 text-success">
                                            <span>Coupon Discount</span>
                                            <span>-₹{{ number_format($masterOrder->coupon_discount, 2) }}</span>
                                        </div>
                                        @endif
                                        <div class="d-flex justify-content-between mb-1">
                                            <span>Subtotal</span>
                                            <span>₹{{ number_format($masterOrder->subtotal, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        @if($masterOrder->tax_amount > 0)
                                        <div class="d-flex justify-content-between mb-1">
                                            <span>Tax Amount</span>
                                            <span>₹{{ number_format($masterOrder->tax_amount, 2) }}</span>
                                        </div>
                                        @endif
                                        @if($masterOrder->shipping_amount > 0)
                                        <div class="d-flex justify-content-between mb-1">
                                            <span>Shipping Charge</span>
                                            <span>₹{{ number_format($masterOrder->shipping_amount, 2) }}</span>
                                        </div>
                                        @else
                                        <div class="d-flex justify-content-between mb-1 ">
                                            <span>Shipping Charge</span>
                                            <span class="text-success">FREE</span>
                                        </div>
                                        @endif
                                        <hr>
                                        <div class="d-flex justify-content-between order-cn fs-5 ">
                                            <span>Grand Total</span>
                                            <span class="text-primary">₹{{ number_format($masterOrder->grand_total, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($masterOrder->coupon_discount > 0)
                            <div class="alert alert-success mt-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-tag-fill me-2"></i>
                                    <div>
                                        <strong>Coupon Applied Successfully!</strong>
                                        <div class="small">You saved ₹{{ number_format($masterOrder->coupon_discount, 2) }} with code: <strong>{{ $masterOrder->coupon_code }}</strong></div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Delivery Information -->
                        <div class="delivery-card border rounded p-4 mt-4">
                            <h6 class="mb-3 text-primary">
                                <i class="bi bi-truck me-2"></i>Delivery Information
                            </h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-start">
                                        <i class="bi bi-calendar-check text-primary me-2 mt-1"></i>
                                        <div>
                                            <small class="text-muted d-block">Estimated Delivery</small>
                                            <strong>{{ \Carbon\Carbon::parse($masterOrder->created_at)->addDays(3)->format('d M, Y') }}</strong>
                                            <small class="text-muted d-block">(4-5 business days)</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-start">
                                        <i class="bi bi-geo-alt text-primary me-2 mt-1"></i>
                                        <div>
                                            <small class="text-muted d-block">Delivery Address</small>
                                            <strong>{{ $masterOrder->city }}, {{ $masterOrder->state }}</strong>
                                            <small class="text-muted d-block">Pincode: {{ $masterOrder->pincode }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Details & Actions Sidebar -->
            <div class="col-lg-4 mb-4">
                <!-- Order Details Card -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0">
        <h5 class="card-title mb-0">
            <i class="bi bi-info-circle me-2"></i>Order Details
        </h5>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <small class="text-muted d-block">Order Number</small>
            <strong>{{ $masterOrder->order_number }}</strong>
        </div>
        <div class="mb-3">
            <small class="text-muted d-block">Order Date</small>
            <strong>{{ $masterOrder->created_at->format('d M, Y h:i A') }}</strong>
        </div>
        <div class="mb-3">
            <small class="text-muted d-block">Order Status</small>
            <span class="badge bg-{{ $color }}">{{ $orderStatus }}</span>
            <small class="text-muted d-block mt-1">
                @if($masterOrder->orderStatus && $masterOrder->orderStatus->remark)
                    <i class="bi bi-info-circle me-1"></i>{{ $masterOrder->orderStatus->remark }}
                @endif
            </small>
        </div>
        <div class="mb-3">
            <small class="text-muted d-block">Payment Status</small>
            <span class="badge bg-{{ $paymentColor }}">{{ $paymentStatus }}</span>
        </div>
        <div class="mb-3">
            <small class="text-muted d-block">Payment Method</small>
            <div class="d-flex align-items-center">
                @if($masterOrder->paymentMethod)
                    @if($masterOrder->paymentMethod->isCOD())
                        <i class="bi bi-cash-stack text-success me-2"></i>
                        <!-- <strong>{{ $masterOrder->paymentMethod->payment_type }}</strong> -->
                        @if($masterOrder->paymentMethod->description)
                            <small class="text-muted ms-2">{{ $masterOrder->paymentMethod->description }}</small>
                        @endif
                    @else
                        <i class="bi bi-credit-card text-primary me-2"></i>
                        <strong>{{ $masterOrder->paymentMethod->payment_type }}</strong>
                        @if($masterOrder->paymentMethod->description)
                            <small class="text-muted ms-2">{{ $masterOrder->paymentMethod->description }}</small>
                        @endif
                    @endif
                @else
                    <i class="bi bi-question-circle text-secondary me-2"></i>
                    <strong>Not Specified</strong>
                @endif
            </div>
        </div>
    </div>
</div>

                <!-- Customer Information Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-person me-2"></i>Customer Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted d-block">Customer Name</small>
                            <strong>{{ $masterOrder->first_name }} {{ $masterOrder->last_name }}</strong>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block">Email Address</small>
                            <strong>{{ $masterOrder->email }}</strong>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block">Phone Number</small>
                            <strong>{{ $masterOrder->phone }}</strong>
                        </div>
                        <div class="mb-0">
                            <small class="text-muted d-block">Shipping Address</small>
                            <strong class="small">{{ $masterOrder->address }}, {{ $masterOrder->city }}, {{ $masterOrder->state }} - {{ $masterOrder->pincode }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons Card -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-lightning me-2"></i>Quick Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ url('/') }}" class="btn ordercon-cash btn-lg">
                                <i class="bi bi-house-door me-2"></i>Continue Shopping
                            </a>
                            <button onclick="window.print()" class="btn btn-outline-secondary btn-lg">
                                <i class="bi bi-printer me-2"></i>Print Invoice
                            </button>
                            <a href="{{ route('order.invoice.download', $masterOrder->id) }}" class="btn btn-outline-primary btn-lg" id="downloadInvoiceBtn">
                                <i class="bi bi-download me-2"></i>Download PDF Invoice
                            </a>
                            <a href="{{ route('order.track', $masterOrder->order_number) }}" class="btn btn-outline-info btn-lg">
                                <i class="bi bi-truck me-2"></i>Track Your Order
                            </a>
                        </div>
                        
                        <!-- Order Summary Mini -->
                        <div class="order-summary-mini mt-4 p-3 border rounded">
                            <h6 class="mb-3 text-center">
                                <i class="bi bi-receipt me-2"></i>Order Summary
                            </h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Items ({{ $masterOrder->items->count() }}):</span>
                                <span>₹{{ number_format($masterOrder->subtotal, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2 ">
                                <span>Total Savings:</span>
                                <span class="text-success">-₹{{ number_format($masterOrder->discount_amount + $masterOrder->coupon_discount, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
    <span>Shipping:</span>
    @if(($masterOrder->shipping_amount ?? 0) > 0)
        <span>₹{{ number_format($masterOrder->shipping_amount, 2) }}</span>
    @else
        <span class="text-success fw-bold">FREE</span>
    @endif
</div>
                            <hr>
                            <div class="d-flex justify-content-between grandtootle">
                                <span>Grand Total:</span>
                                <span class="text-primary">₹{{ number_format($masterOrder->grand_total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Important Notes Section -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="mb-3 text-primary">
                            <i class="bi bi-info-circle me-2"></i>Important Information
                        </h6>
                        <div class="row Important-Information">
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-envelope text-primary me-2 mt-1"></i>
                                    <div>
                                        <small class="text-muted d-block">Email Confirmation</small>
                                        <small>An order confirmation has been sent to <p>{{ $masterOrder->email }}</p></small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-clock text-primary me-2 mt-1"></i>
                                    <div>
                                        <small class="text-muted d-block">Order Processing</small>
                                        <small>Your order will be processed within <span>24 hours</span></small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-telephone text-primary me-2 mt-1"></i>
                                    <div>
                                        <small class="text-muted d-block">Need Help?</small>
                                        <small>Call us at : <span>{{ $setting->phone ?? '' }}</span></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Order Tracking Modal -->
<div class="modal fade" id="trackOrderModal" tabindex="-1" aria-labelledby="trackOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="trackOrderModalLabel">
                    <i class="bi bi-truck me-2"></i>Track Order #{{ $masterOrder->order_number }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Order tracking timeline will be loaded here -->
            </div>
        </div>
    </div>
</div>

@include('userfooter')

<style>
/* Custom CSS for Order Confirmation */
.order-timeline .step {
    text-align: center;
    position: relative;
    flex: 1;
}

.order-timeline .step-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #f8f9fa;
    border: 3px solid #dee2e6;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    font-size: 1.2rem;
    color: #6c757d;
}

.order-timeline .step.completed .step-icon {
    background: #198754;
    border-color: #198754;
    color: white;
}

.order-timeline .step.active .step-icon {
    background: #0d6efd;
    border-color: #0d6efd;
    color: white;
    animation: pulse 2s infinite;
}

.order-timeline .step-title {
    font-size: 0.9rem;
    font-weight: 600;
}
.ordercon-cash{
    background-color: #cb7000;
    color:#fff;
}

.grandtootle{
    color:#cb7000;
    font-weight: 700 ;
    font-size: 1.2rem;
}
.grandtootle span:nth-child(2){
     color: #404040 !important;
}

.order-cn{
    color: #cb7000;
    font-weight: 700;
}

.order-cn span:nth-child(2){
        color: #404040 !important;
}

.order-con-price{
    color: #383838;
}

.card-header h5{
    color: #3e3c3c !Important;
}
/*.card{*/
/*    background: transparent;*/
/*}*/
/* Timeline Styles */
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -30px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background-color: #007bff;
}

.timeline-content {
    padding-bottom: 10px;
    border-bottom: 1px solid #e9ecef;
}

.timeline-item:last-child .timeline-content {
    border-bottom: none;
}
b, strong{
        color: #3a3a3a;
}
.table-borderless>:not(caption)>*>* {
    border-bottom-width: 0;
    color: #3a3a3a;
}

.text-success{
    color: #009334 !important;
    font-weight: 500;
}
.card-body{
   text-align: left;
}
.bg-primary {
   
    background-color: rgb(203 112 0) !important;
}
.Important-Information small p{
    font-weight: 500;
    color: #cb7000;
}
.Important-Information small span{
    font-weight: 500;
    color: #cb7000;
}

.bg-warning {
    --bs-bg-opacity: 1;
    background-color: rgb(203 112 0) !important;
}
/* Badge Colors */
.badge-warning { background-color: #ffc107; color: #000; }
.badge-info { background-color: #17a2b8; color: #fff; }
.badge-primary { background-color: #007bff; color: #fff; }
.badge-success { background-color: #28a745; color: #fff; }
.badge-dark { background-color: #343a40; color: #fff; }
.badge-danger { background-color: #dc3545; color: #fff; }
.badge-secondary { background-color: #6c757d; color: #fff; }

@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(13, 110, 253, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(13, 110, 253, 0); }
    100% { box-shadow: 0 0 0 0 rgba(13, 110, 253, 0); }
}

.order-items-table tbody tr:hover {
    background: #f8f9fa;
    transition: all 0.3s;
}

.price-breakdown-card {
    background: linear-gradient(135deg, #f8f9fa4d, #e9ecef47);
}

.delivery-card {
    background: #e7f3ff5c;
    border-left: 4px solid #cb7000 !important;
}

.action-buttons .btn {
    border-radius: 10px;
    padding: 12px;
    transition: all 0.3s;
}

.action-buttons .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.order-summary-mini {
    background: #f8f9fa;
}

/* Responsive Design */

@media (max-width: 500px) {
.order-timeline .step-title{
    font-size: 0.6rem;
}
.order-timeline .step  small {
font-size: 0.5em;
}

.card-body{
    padding:0px
}
}

@media (max-width: 768px) {
    .order-timeline .step-icon {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
    
    .step-title {
        font-size: 0.8rem;
    }
}

/* Print Styles */
@media print {
    .card, .alert, .action-buttons, .product_listbanner, .modal {
        display: none !important;
    }
    
    .container {
        width: 100%;
        max-width: none;
    }
    
    .card {
        border: 1px solid #000 !important;
        box-shadow: none !important;
    }
    
    .card-body {
        padding: 0 !important;
    }
    
    .table {
        font-size: 12px;
    }
}


/*timeline css*/
.status-bar{
    display:flex;
    justify-content:space-between;
    position:relative;
}
.status-bar::before{
    content:'';
    position:absolute;
    top:32px;
    left:0;
    right:0;
    height:3px;
    background:#e5e5e5;
}
.status-step{
    width:16%;
    text-align:center;
    position:relative;
    z-index:1;
}
.status-step .icon{
    width:55px;
    height:55px;
    border-radius:50%;
    background:#fff;
    border:3px solid #ddd;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:0 auto 8px;
    font-size:20px;
    color:#aaa;
}
.status-step.done .icon{
    background:#28a745;
    border-color:#28a745;
    color:#fff;
}
.status-step p{
    font-size:12px;
    color:#777;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add animation to cards
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });

    // Download PDF Invoice button
    const downloadBtn = document.getElementById('downloadInvoiceBtn');
    if (downloadBtn) {
        downloadBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const originalText = this.innerHTML;
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Generating PDF...';
            this.disabled = true;
            
            // Simulate download delay
            setTimeout(() => {
                window.location.href = this.href;
                this.innerHTML = originalText;
                this.disabled = false;
            }, 800);
        });
    }

    // Print functionality
    window.addEventListener('beforeprint', function() {
        // Add print-specific styles
        document.body.classList.add('print-mode');
    });

    window.addEventListener('afterprint', function() {
        // Remove print-specific styles
        document.body.classList.remove('print-mode');
    });

    // Order tracking modal
    const trackModal = new bootstrap.Modal(document.getElementById('trackOrderModal'));
    const trackButtons = document.querySelectorAll('[href*="order.track"]');
    
    trackButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            trackModal.show();
        });
    });
});
</script>