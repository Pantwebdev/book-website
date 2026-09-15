@include('userheader')

<div class="product_listbanner">
    <img src="{{ url('userassets/image/banner-list.png') }}" alt="">
</div>

<section>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="payment-container">
                    <!-- Order Summary -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0">
                                <i class="bi bi-credit-card me-2"></i>
                                Processing Your Payment
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Order Details</h6>
                                    <p class="mb-1"><strong>Order ID:</strong> {{ $masterOrder->order_number }}</p>
                                    <p class="mb-1"><strong>Date:</strong> {{ $masterOrder->created_at->format('d M, Y h:i A') }}</p>
                                    <p class="mb-0"><strong>Payment Method:</strong> Online Payment</p>
                                </div>
                                <div class="col-md-6 text-end">
                                    <h6>Total Amount</h6>
                                    <h2 class="text-primary">₹{{ number_format($masterOrder->grand_total, 2) }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Payment Options -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="bi bi-shield-check me-2"></i>
                                Secure Payment
                            </h6>
                        </div>
                        <div class="card-body">
                            <!-- Razorpay Payment Button -->
                            <div class="text-center">
                                <button id="rzp-button" class="btn btn-primary btn-lg">
                                    <i class="bi bi-lock-fill me-2"></i>
                                    Pay ₹{{ number_format($masterOrder->grand_total, 2) }}
                                </button>
                                
                                <div class="mt-3">
                                    <small class="text-muted">
                                        <i class="bi bi-shield-check"></i>
                                        Secured by Razorpay | Supports Credit/Debit Cards, Net Banking, UPI & Wallets
                                    </small>
                                </div>
                            </div>
                            
                            <!-- Payment Status -->
                            <div id="payment-status" class="mt-4" style="display: none;">
                                <div class="alert alert-info">
                                    <div class="d-flex align-items-center">
                                        <div class="spinner-border spinner-border-sm me-3" role="status"></div>
                                        <div>
                                            <strong>Processing your payment...</strong>
                                            <div class="small">Please do not refresh the page</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Alternative Options -->
                            <div class="mt-4">
                                <h6>Having trouble with payment?</h6>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('payment.cancel', ['order_id' => $masterOrder->id]) }}" 
                                       class="btn btn-outline-secondary">
                                        Cancel Order
                                    </a>
                                    <a href="{{ route('cart.view') }}" class="btn btn-outline-primary">
                                        Back to Cart
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Order Items Summary -->
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="bi bi-receipt me-2"></i>
                                Order Summary
                            </h6>
                        </div>
                        <div class="card-body">
                            @foreach($masterOrder->items as $item)
                            <div class="d-flex border-bottom pb-3 mb-3">
                                <div class="flex-shrink-0">
                                    <img src="{{ url('userassets/image/product/' . $item->image) }}" 
                                         alt="{{ $item->product_name }}" 
                                         style="width: 70px; height: 70px; object-fit: cover; border-radius: 8px;">
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">{{ $item->product_name }}</h6>
                                    <div class="d-flex flex-wrap gap-2">
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
                                            Qty: {{ $item->qty }}
                                        </span>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold">₹{{ number_format($item->item_total, 2) }}</div>
                                </div>
                            </div>
                            @endforeach
                            
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <h6>Billing Address</h6>
                                    <p class="mb-0">
                                        {{ $masterOrder->first_name }} {{ $masterOrder->last_name }}<br>
                                        {{ $masterOrder->address }}<br>
                                        {{ $masterOrder->city }}, {{ $masterOrder->state }} - {{ $masterOrder->pincode }}<br>
                                        Phone: {{ $masterOrder->phone }}
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <h6>Price Breakdown</h6>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Subtotal:</span>
                                        <span>₹{{ number_format($masterOrder->subtotal, 2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Discount:</span>
                                        <span class="text-success">-₹{{ number_format($masterOrder->discount_amount + $masterOrder->coupon_discount, 2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Tax:</span>
                                        <span>₹{{ number_format($masterOrder->tax_amount, 2) }}</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between fw-bold">
                                        <span>Total:</span>
                                        <span class="text-primary">₹{{ number_format($masterOrder->grand_total, 2) }}</span>
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

@include('userfooter')

<!-- Razorpay Integration -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const rzpButton = document.getElementById('rzp-button');
    const paymentStatus = document.getElementById('payment-status');
    
    rzpButton.addEventListener('click', async function(e) {
        e.preventDefault();
        
        // Show loading
        rzpButton.disabled = true;
        rzpButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
        paymentStatus.style.display = 'block';
        
        try {
            // Create Razorpay order via AJAX
            const response = await fetch('{{ route("create.razorpay.order") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    order_id: '{{ $masterOrder->id }}'
                })
            });
            
            const data = await response.json();
            
            if (!data.success) {
                throw new Error(data.error || 'Unable to create payment order');
            }
            
            // Razorpay Options
            const options = {
                key: data.key,
                amount: data.amount,
                currency: "INR",
                name: data.name,
                description: data.description,
                order_id: data.order_id,
                handler: function(response) {
                    // On successful payment
                    handlePaymentSuccess(response);
                },
                prefill: data.prefill,
                notes: data.notes,
                theme: data.theme,
                modal: {
                    ondismiss: function() {
                        // If user closes the modal
                        rzpButton.disabled = false;
                        rzpButton.innerHTML = '<i class="bi bi-lock-fill me-2"></i>Pay ₹{{ number_format($masterOrder->grand_total, 2) }}';
                        paymentStatus.style.display = 'none';
                    }
                }
            };
            
            // Create Razorpay instance
            const rzp = new Razorpay(options);
            
            // Handle payment failure
            rzp.on('payment.failed', function(response) {
                handlePaymentFailure(response);
            });
            
            // Open Razorpay checkout
            rzp.open();
            
        } catch (error) {
            console.error('Payment initialization error:', error);
            alert('Error: ' + error.message);
            
            // Reset button
            rzpButton.disabled = false;
            rzpButton.innerHTML = '<i class="bi bi-lock-fill me-2"></i>Pay ₹{{ number_format($masterOrder->grand_total, 2) }}';
            paymentStatus.style.display = 'none';
        }
    });
    
    // Handle successful payment
    async function handlePaymentSuccess(response) {
        try {
            // Submit form to server for verification
            const formData = new FormData();
            formData.append('razorpay_payment_id', response.razorpay_payment_id);
            formData.append('razorpay_order_id', response.razorpay_order_id);
            formData.append('razorpay_signature', response.razorpay_signature);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            const verifyResponse = await fetch('{{ route("payment.success", ["order_id" => $masterOrder->id]) }}', {
                method: 'POST',
                body: formData
            });
            
            if (verifyResponse.ok) {
                // Redirect to success page
                window.location.href = '{{ route("order.confirmation", ["order_id" => $masterOrder->id]) }}';
            } else {
                throw new Error('Payment verification failed');
            }
            
        } catch (error) {
            console.error('Payment verification error:', error);
            
            // Try alternative method - redirect with GET parameters
            const params = new URLSearchParams({
                razorpay_payment_id: response.razorpay_payment_id,
                razorpay_order_id: response.razorpay_order_id,
                razorpay_signature: response.razorpay_signature
            });
            
            window.location.href = '{{ route("payment.success", ["order_id" => $masterOrder->id]) }}?' + params.toString();
        }
    }
    
    // Handle payment failure
    function handlePaymentFailure(response) {
        // Redirect to failure page
        window.location.href = '{{ route("payment.failure", ["order_id" => $masterOrder->id]) }}';
    }
    
    // Auto-click payment button after 2 seconds
    setTimeout(() => {
        rzpButton.click();
    }, 800);
});
</script>

<style>
.payment-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px 0;
}

.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    margin-bottom: 20px;
    transition: transform 0.3s;
}

.card:hover {
    transform: translateY(-2px);
}

.card-header {
    border-radius: 12px 12px 0 0 !important;
    font-weight: 600;
}

#rzp-button {
    padding: 15px 40px;
    font-size: 18px;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0,123,255,0.3);
    transition: all 0.3s;
}

#rzp-button:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,123,255,0.4);
}

#rzp-button:disabled {
    opacity: 0.7;
}

.alert {
    border-radius: 10px;
    border: none;
}

.badge {
    font-size: 0.8em;
    padding: 5px 10px;
    border-radius: 20px;
}

@media (max-width: 768px) {
    .payment-container {
        padding: 10px;
    }
    
    #rzp-button {
        width: 100%;
        padding: 12px;
    }
}
</style>