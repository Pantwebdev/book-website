@include('userheader')
<section class="py-5">
    <div class="container">
        <div class="card track-card">

            <!-- HEADER -->
            <div class="track-header">
                <i class="bi bi-truck"></i>
                Track Order #{{ $masterOrder->order_number }}
            </div>

           <!-- ORDER STATUS -->
@php
    $statusIcons = [
        'Order Placed'    => 'bi-cart',
        'Confirmed'       => 'bi-check-circle',
        'Processing'      => 'bi-gear',
        'Packed'          => 'bi-box',
        'Shipped'         => 'bi-truck',
        'Out for Delivery'=> 'bi-truck-flatbed',
        'Delivered'       => 'bi-house',
        'Cancelled'       => 'bi-x-circle',
        'Returned'        => 'bi-arrow-return-left',
        'Refunded'        => 'bi-currency-exchange',
    ];

    $statusDates = [
        'Order Placed'    => $masterOrder->created_at,
        'Confirmed'       => $masterOrder->confirmed_at,
        'Processing'      => $masterOrder->confirmed_at,
        'Packed'          => $masterOrder->updated_at,
        'Shipped'         => $masterOrder->shipped_at,
        'Out for Delivery'=> $masterOrder->updated_at,
        'Delivered'       => $masterOrder->delivered_at,
        'Cancelled'       => $masterOrder->cancelled_at,
        'Returned'        => $masterOrder->updated_at,
        'Refunded'        => $masterOrder->updated_at,
    ];

    $currentStatus = $masterOrder->orderStatus->order_status ?? 'Order Placed';
    $statusNames   = $activeStatuses->pluck('order_status')->toArray();
    $currentIndex  = array_search($currentStatus, $statusNames);
@endphp

<div class="p-4 text-center">
    <h5 class="text-primary mb-4">Order Status</h5>

    <div class="status-bar">
        @foreach($activeStatuses as $status)
            @php
                $name      = $status->order_status;
                $icon      = $statusIcons[$name]  ?? 'bi-circle';
                $date      = $statusDates[$name]  ?? null;
                $checkIdx  = array_search($name, $statusNames);
                $done      = ($checkIdx !== false && $currentIndex !== false && $checkIdx <= $currentIndex);
            @endphp

            <div class="status-step {{ $done ? 'done' : '' }}">
                <div class="icon">
                    <i class="bi {{ $icon }}"></i>
                </div>

                <strong>{{ $name }}</strong>

                @if($done && $date)
                    <p class="text-muted">
                        {{ \Carbon\Carbon::parse($date)->format('d M Y, h:i A') }}
                    </p>
                @else
                    <p class="text-muted">Pending</p>
                @endif

                @if($checkIdx === $currentIndex)
                    <span class="badge bg-success">Current</span>
                @endif
            </div>
        @endforeach
    </div>
</div>

            <!-- DETAILS -->
            <div class="row px-4 pb-4">
                <div class="col-md-6">
                    <div class="info-box">
                        <h6><i class="bi bi-info-circle"></i> Order Details</h6>
                        <p><b>Order Number:</b> {{ $masterOrder->order_number }}</p>
                        <p><b>Order Date:</b> {{ $masterOrder->created_at->format('d M, Y h:i A') }}</p>
                        <p><b>Order Status:</b> {{ $masterOrder->order_status_label }}</p>
                        <p><b>Payment Method:</b> {{ $masterOrder->payment_method_label }}</p>
                        <p><b>Payment Status:</b> {{ $masterOrder->payment_status_label }}</p>
                        <p class="total">₹{{ number_format($masterOrder->grand_total,2) }}</p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="info-box">
                        <h6><i class="bi bi-person"></i> Customer Details</h6>
                        <p><b>Name:</b> {{ $masterOrder->full_name }}</p>
                        <p><b>Email:</b> {{ $masterOrder->email }}</p>
                        <p><b>Phone:</b> {{ $masterOrder->phone }}</p>
                        <p><b>Shipping Address:</b><br>
                            @if($masterOrder->shipping_address)
                                {{ $masterOrder->shipping_address }},
                                {{ $masterOrder->shipping_city }},
                                {{ $masterOrder->shipping_state }} -
                                {{ $masterOrder->shipping_pincode }}
                            @else
                                {{ $masterOrder->address }},
                                {{ $masterOrder->city }},
                                {{ $masterOrder->state }} -
                                {{ $masterOrder->pincode }}
                            @endif
                            </p>

                    </div>
                </div>
            </div>

            <!-- DELIVERY -->
            <div class="delivery-box">
                <i class="bi bi-info-circle"></i>
                Your order is expected to be delivered by
                <b>{{ $masterOrder->created_at->addDays(5)->format('d M, Y') }}</b>
            </div>

            <!-- ACTIONS -->
            <div class="text-center py-4">
                <a href="{{ route('order.confirmation',$masterOrder->id) }}" class="btn btn-primary">View Order Details</a>
                <a href="{{ route('order.invoice.download',$masterOrder->id) }}" class="btn btn-link">Download Invoice</a>
                <a href="{{ url('/') }}" class="btn btn-outline-secondary">Continue Shopping</a>
            </div>

        </div>
    </div>
</section>

@include('userfooter')
<style>
.track-card{
    border-radius:10px;
    overflow:hidden;
}
.track-header{
    background:#0d6efd;
    color:#fff;
    padding:15px 20px;
    font-size:18px;
    font-weight:600;
}
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
.info-box{
    border:1px solid #eee;
    border-radius:8px;
    padding:15px;
    height:100%;
}
.info-box h6{
    font-weight:600;
    margin-bottom:15px;
}
.info-box .total{
    font-weight:700;
    color:#0d6efd;
}
.delivery-box{
    background:#dff6ff;
    padding:15px;
    text-align:center;
    font-weight:500;
}
</style>
