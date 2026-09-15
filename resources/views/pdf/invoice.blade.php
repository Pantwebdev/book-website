<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Invoice {{ $order->order_number }}</title>

<style>
body{font-family:DejaVu Sans;font-size:12px;color:#333}
.container{width:100%;padding:20px}
.table{width:100%;border-collapse:collapse}
.table th,.table td{border:1px solid #ddd;padding:8px;font-size:11px}
.table th{background:#f5f5f5}
.text-right{text-align:right}
.text-center{text-align:center}
.section-title{
    background:#007bff;color:#fff;padding:6px 10px;
    font-weight:bold;margin-top:20px;font-size:12px
}
.logo{max-width:150px}
.product-img{width:50px;height:50px;border:1px solid #ddd}
.watermark{
    position:absolute;top:350px;left:120px;
    transform:rotate(-45deg);
    font-size:80px;color:rgba(0,0,0,0.05)
}
.status-badge{
    background:#28a745;
    color:white;
    padding:3px 8px;
    border-radius:3px;
    font-size:10px;
}
.payment-badge{
    background:#007bff;
    color:white;
    padding:3px 8px;
    border-radius:3px;
    font-size:10px;
}
</style>
</head>

<body>
<div class="watermark">{{ $company['name'] }}</div>

<div class="container">

<!-- HEADER -->
<table class="table" style="border:none">
<tr style="border:none">
<td style="border:none" width="60%">
@if(file_exists(public_path('userassets/image/logo.png')))
<img src="{{ 'data:image/png;base64,'.base64_encode(file_get_contents(public_path('userassets/image/logo.png'))) }}" class="logo">
@endif
<br><strong>{{ $company['name'] }}</strong><br>
{{ $company['address'] }}<br>
Phone: {{ $company['phone'] }}<br>
Email: {{ $company['email'] }}<br>
GSTIN: {{ $company['gstin'] }}
</td>

<td style="border:none" width="40%" class="text-right">
<h2>TAX INVOICE</h2>
Invoice No: {{ $order->order_number }}<br>
Invoice Date: {{ $order->created_at->format('d/m/Y') }}<br>
Order Date: {{ $order->created_at->format('d M Y h:i A') }}<br>
Payment: <span class="payment-badge">{{ strtoupper($paymentMethod) }}</span><br>
Payment Status: <span class="status-badge">{{ strtoupper($paymentStatus) }}</span><br>
Order Status: <span class="status-badge">{{ strtoupper($orderStatus) }}</span>
</td>
</tr>
</table>

<!-- BILLING -->
<div class="section-title">BILLING & SHIPPING INFORMATION</div>
<table class="table">
<tr>
<td width="50%">
<strong>Bill To</strong><br>
{{ $order->first_name }} {{ $order->last_name }}<br>
{{ $order->email }}<br>
{{ $order->phone }}<br>
{{ $order->address }}<br>
{{ $order->city }}, {{ $order->state }}<br>
{{ $order->pincode }}, {{ $order->country }}
</td>

<td width="50%">
@if($order->shipping_address)
<strong>Ship To</strong><br>
{{ $order->shipping_first_name }} {{ $order->shipping_last_name }}<br>
{{ $order->shipping_phone }}<br>
{{ $order->shipping_address }}<br>
{{ $order->shipping_city }}, {{ $order->shipping_state }}<br>
{{ $order->shipping_pincode }}
@else
<strong>Shipping Address (Same as Billing)</strong><br>
{{ $order->address }}<br>
{{ $order->city }}, {{ $order->state }}<br>
{{ $order->pincode }}, {{ $order->country }}
@endif
</td>
</tr>
</table>

<!-- ITEMS -->
<div class="section-title">ORDER ITEMS</div>
<table class="table">
<tr>
<th>#</th>
<th>Image</th>
<th>Description</th>
<th>Qty</th>
<th>Unit Price</th>
<th>Total</th>
</tr>

@foreach($items as $k=>$item)
<tr>
<td class="text-center">{{ $k+1 }}</td>
<td class="text-center">
@if($item['image_base64'])
<img src="{{ $item['image_base64'] }}" class="product-img">
@endif
</td>
<td>
<strong>{{ $item['product_name'] }}</strong><br>
ISBN: {{ $item['sku'] }}<br>
@if($item['color'] || $item['size'])
Color: {{ $item['color'] ?? 'N/A' }} | Size: {{ $item['size'] ?? 'N/A' }}
@endif
</td>
<td class="text-center">{{ $item['qty'] }}</td>
<td class="text-center">₹{{ number_format($item['price'],2) }}</td>
<td class="text-center">₹{{ number_format($item['item_total'],2) }}</td>
</tr>
@endforeach
</table>

<!-- TOTAL -->
<div class="section-title">PRICE SUMMARY</div>
<table class="table">

<tr>
    <td width="70%">Total MRP</td>
    <td width="30%" class="text-right">₹{{ number_format($order->total_mrp, 2) }}</td>
</tr>

<tr>
    <td>Discount on MRP</td>
    <td class="text-right" style="color:green;">- ₹{{ number_format($order->discount_amount, 2) }}</td>
</tr>

@if($order->coupon_discount > 0)
<tr>
    <td>Coupon Discount ({{ $order->coupon_code }})</td>
    <td class="text-right" style="color:green;">- ₹{{ number_format($order->coupon_discount, 2) }}</td>
</tr>
@endif

<tr>
    <td>Subtotal</td>
    <td class="text-right">₹{{ number_format($order->subtotal, 2) }}</td>
</tr>

<tr>
    <td>Shipping Charge</td>
    <td class="text-right">
        @if(($order->shipping_amount ?? 0) > 0)
            ₹{{ number_format($order->shipping_amount, 2) }}
        @else
            <span style="color:green;">FREE</span>
        @endif
    </td>
</tr>

<tr style="border-top:2px solid #333; background:#f5f5f5;">
    <th>GRAND TOTAL</th>
    <th class="text-right">₹{{ number_format($order->grand_total, 2) }}</th>
</tr>

</table>

<!-- TAX DETAILS (Optional) -->


<!-- FOOTER -->
<div style="margin-top:30px;font-size:10px;text-align:center;border-top:1px solid #ddd;padding-top:10px">
<p><strong>Important Notes:</strong></p>
<p>1. This is a computer generated invoice and does not require signature.</p>
<p>2. Goods once sold will not be taken back or exchanged.</p>
<p>3. For any queries, please contact our customer support.</p>
<p>{{ $company['name'] }} | {{ $company['phone'] }} | {{ $company['email'] }} | {{ $company['website'] }}</p>
</div>

</div>
</body>
</html>