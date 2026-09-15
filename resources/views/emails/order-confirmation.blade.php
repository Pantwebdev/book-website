<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order Confirmation - {{ $order->order_number }}</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            line-height: 1.6; 
            color: #333; 
            max-width: 700px; 
            margin: 0 auto; 
            padding: 0; 
            background: #f5f5f5;
        }
        .email-container {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            margin: 20px auto;
        }
        .header { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white; 
            padding: 30px 20px; 
            text-align: center; 
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .content { 
            padding: 30px; 
        }
        .order-details { 
            background: #f8f9fa; 
            border-radius: 10px; 
            padding: 20px; 
            margin: 20px 0; 
            border-left: 4px solid #667eea;
        }
        .order-items { 
            margin: 25px 0; 
        }
        .order-item { 
            display: flex; 
            justify-content: space-between; 
            padding: 15px; 
            border-bottom: 1px solid #eee; 
            background: white;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .order-item:last-child {
            border-bottom: none;
        }
        .total-section { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px; 
            border-radius: 10px; 
            margin: 25px 0; 
        }
        .footer { 
            text-align: center; 
            margin-top: 30px; 
            color: #666; 
            font-size: 14px; 
            padding: 20px;
            border-top: 1px solid #eee;
        }
        .btn { 
            display: inline-block; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white; 
            padding: 12px 30px; 
            text-decoration: none; 
            border-radius: 5px; 
            margin: 15px 0; 
            font-weight: bold;
            text-align: center;
        }
        .badge { 
            background: #28a745; 
            color: white; 
            padding: 5px 10px; 
            border-radius: 5px; 
            font-size: 12px; 
            display: inline-block;
        }
        .info-box { 
            background: #e7f3ff; 
            padding: 20px; 
            border-radius: 10px; 
            margin: 20px 0; 
            border-left: 4px solid #007bff; 
        }
        .status-badge {
            background: #ffc107;
            color: #000;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
            display: inline-block;
        }
        .customer-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }
        .price-breakdown {
            background: white;
            border-radius: 10px;
            padding: 20px;
            border: 1px solid #eee;
            margin: 20px 0;
        }
        .price-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .price-row.total {
            border-top: 2px solid #667eea;
            font-weight: bold;
            font-size: 18px;
            padding-top: 15px;
            margin-top: 10px;
        }
        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 15px;
        }
        .product-details {
            flex-grow: 1;
        }
        .highlight {
            color: #667eea;
            font-weight: bold;
        }
        .text-success {
            color: #28a745;
        }
        .text-danger {
            color: #dc3545;
        }
        .text-muted {
            color: #6c757d;
        }
        
        /* Status badge colors */
        .status-pending { background: #ffc107; color: #000; }
        .status-confirmed { background: #17a2b8; color: #fff; }
        .status-processing { background: #007bff; color: #fff; }
        .status-shipped { background: #28a745; color: #fff; }
        .status-delivered { background: #343a40; color: #fff; }
        .status-cancelled { background: #dc3545; color: #fff; }
        .status-refunded { background: #6c757d; color: #fff; }
        
        .payment-pending { background: #ffc107; color: #000; }
        .payment-initiated { background: #17a2b8; color: #fff; }
        .payment-success { background: #28a745; color: #fff; }
        .payment-collected { background: #28a745; color: #fff; }
        .payment-failed { background: #dc3545; color: #fff; }
        .payment-cancelled { background: #dc3545; color: #fff; }
        
        @media (max-width: 600px) {
            .content {
                padding: 15px;
            }
            .header h1 {
                font-size: 22px;
            }
            .order-item {
                flex-direction: column;
            }
            .price-row {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>🎉 Order {{ $isForAdmin ? 'Received' : 'Confirmed' }}!</h1>
            <p style="margin: 10px 0 0; opacity: 0.9;">
                @if(!$isForAdmin)
                Thank you for shopping with us!
                @else
                New order has been placed on your store
                @endif
            </p>
        </div>
        
        <div class="content">
            @if(!$isForAdmin)
            <div class="info-box">
                <h3 style="margin-top: 0;">Dear {{ $order->first_name }},</h3>
                <p>Your order <strong>{{ $order->order_number }}</strong> has been successfully placed and is being processed. Here are your order details:</p>
            </div>
            @else
            <div class="info-box">
                <h3 style="margin-top: 0;">📋 New Order Notification</h3>
                <p>A new order has been placed by <strong>{{ $order->first_name }} {{ $order->last_name }}</strong>.</p>
            </div>
            @endif
            
            <div class="order-details">
                <h3 style="margin-top: 0; color: #667eea;">📦 Order Summary</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 15px;">
                    <div>
                        <p style="margin: 5px 0;"><strong>Order Number:</strong><br>{{ $order->order_number }}</p>
                    </div>
                    <div>
                        <p style="margin: 5px 0;"><strong>Order Date:</strong><br>{{ $order->created_at->format('d M, Y h:i A') }}</p>
                    </div>
                    <div>
                        <p style="margin: 5px 0;"><strong>Order Status:</strong><br>
                            @php
                                $orderStatus = $order->orderStatus;
                                $orderStatusName = $orderStatus ? $orderStatus->order_status : 'Pending';
                                $statusColorClass = 'status-' . strtolower($orderStatusName);
                            @endphp
                            <span class="badge {{ $statusColorClass }}">{{ $orderStatusName }}</span>
                        </p>
                    </div>
                    <div>
                        <p style="margin: 5px 0;"><strong>Payment Method:</strong><br>
                            @php
                                $paymentMethod = $order->paymentMethod;
                                $paymentMethodName = $paymentMethod ? $paymentMethod->description : 'Unknown';
                            @endphp
                            {{ $paymentMethodName }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="order-items">
                <h3 style="margin-top: 0; color: #667eea;">🛍️ Order Items ({{ $order->items->count() }})</h3>
                @foreach($order->items as $item)
                <div class="order-item">
                    <div style="display: flex; align-items: center;">
                        <div class="product-details">
                            <strong>{{ $item->product_name }}</strong>
                            @if($item->color || $item->size)
                            <div style="font-size: 13px; color: #666; margin-top: 5px;">
                                @if($item->color) <span style="display: inline-block; margin-right: 10px;">Color:{{ $item->color }}</span> @endif
                                @if($item->size) <span style="display: inline-block;"> Size:{{ $item->size }}</span> @endif
                            </div>
                            @endif
                            <div style="font-size: 13px; color: #666; margin-top: 5px;">
                                <span style="display: inline-block; margin-right: 10px;">ISBN: {{ $item->sku }}</span>
                                <span style="display: inline-block;">Qty: {{ $item->qty }}</span>
                            </div>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-weight: bold; font-size: 16px; color: #333;">₹{{ number_format($item->item_total, 2) }}</div>
                        @if($item->mrp_price > $item->price)
                        <div style="font-size: 13px; color: #28a745; margin-top: 5px;">
                            <span style="text-decoration: line-through; color: #999;">₹{{ number_format($item->item_mrp_total, 2) }}</span>
                            <span style="display: block;">Saved: ₹{{ number_format($item->item_mrp_total - $item->item_total, 2) }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="price-breakdown">
                <h3 style="margin-top: 0; color: #667eea;">💰 Price Summary</h3>
                <div class="price-row">
                    <span>Items Total ({{ $order->items->sum('qty') }} items):</span>
                    <span>₹{{ number_format($order->subtotal, 2) }}</span>
                </div>
                
                @if($order->discount_amount > 0)
                <div class="price-row text-success">
                    <span>Product Discount:</span>
                    <span>- ₹{{ number_format($order->discount_amount, 2) }}</span>
                </div>
                @endif
                
                @if($order->coupon_discount > 0)
                <div class="price-row text-success">
                    <span>Coupon Discount ({{ $order->coupon_code }}):</span>
                    <span>- ₹{{ number_format($order->coupon_discount, 2) }}</span>
                </div>
                @endif
                
                @if($order->tax_amount > 0)
                <div class="price-row">
                    <span>Tax Amount:</span>
                    <span>₹{{ number_format($order->tax_amount, 2) }}</span>
                </div>
                @endif
                
                <div class="price-row total">
                    <span>Grand Total:</span>
                    <span class="highlight">₹{{ number_format($order->grand_total, 2) }}</span>
                </div>
            </div>
            
            <div class="customer-info">
                <h3 style="margin-top: 0; color: #667eea;">👤 Customer & Payment Information</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 15px;">
                    <div>
                        <p style="margin: 5px 0;"><strong>Name:</strong><br>{{ $order->first_name }} {{ $order->last_name }}</p>
                        <p style="margin: 5px 0;"><strong>Email:</strong><br>{{ $order->email }}</p>
                    </div>
                    <div>
                        <p style="margin: 5px 0;"><strong>Phone:</strong><br>{{ $order->phone }}</p>
                        <p style="margin: 5px 0;"><strong>Address:</strong><br>{{ $order->address }}, {{ $order->city }}, {{ $order->state }} - {{ $order->pincode }}</p>
                        <p style="margin: 5px 0;"><strong>Payment Status:</strong><br>
                            @php
                                $paymentStatus = $order->paymentStatus;
                                $paymentStatusName = $paymentStatus ? $paymentStatus->payment_status : 'Pending';
                                $paymentColorClass = 'payment-' . strtolower($paymentStatusName);
                            @endphp
                            <span class="badge {{ $paymentColorClass }}">{{ $paymentStatusName }}</span>
                        </p>
                    </div>
                </div>
            </div>
            
           
            
            <div class="footer">
                <p style="margin: 0 0 10px;">Need help with your order?</p>
                <p style="margin: 0 0 10px;">
                    📧 Email: info@ajhuie.com | 
                    📞 Phone: +91-9711475393 | 
                    🌐 Website: {{ config('app.url') }}
                </p>
                <p style="margin: 0; color: #999; font-size: 12px;">
                    © {{ date('Y') }}Ajhuie Book store . All rights reserved.
                </p>
            </div>
        </div>
    </div>
</body>
</html>