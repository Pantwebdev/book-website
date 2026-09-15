@include('userheader')

<div class="product_listbanner">
    <img src="{{url('userassets/image/banner-list.png')}}" alt="">
</div>

<section>
    <div class="checkout-steps text-center">
        <span class="active" data-step="1">Personal Information</span>
        <span data-step="2">Order Summary </span>
    </div>
    <div class="container checkout-wrapper">
        <form id="checkoutForm" action="{{ route('order.place') }}" method="POST">
            @csrf
            <div class="checkout-step" id="step1">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="checkout-box">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="section-title mb-0">Personal Information</h4>
                                @if(!auth()->check())
                                <div class="logincheckout">
                                    <a href="{{ route('login') }}" class="logincheckout">Login</a> /
                                    <a href="{{ route('register') }}" class="logincheckout">Signup</a>
                                </div>
                                @endif
                            </div>
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="bi bi-person-circle me-2"></i>
                                        Customer Information
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @if(auth()->guard('customer')->check())
                                        @php
                                            $customer = auth()->guard('customer')->user();
                                        @endphp
                                        <div class="row">
                                            <div class="col-12">
                                                <p class="mb-1"><strong>Logged in as:</strong> {{ $customer->name ?? $customer->first_name . ' ' . $customer->last_name }}</p>
                                                <p class="mb-1"><strong>Email:</strong> {{ $customer->email }}</p>
                                                @if($customer->phone)
                                                    <p class="mb-0"><strong>Phone:</strong> {{ $customer->phone }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <p class="text-muted mb-0">You are checking out as a guest</p>
                                    @endif
                                </div>
                            </div>
                            <h6 class="mb-3 text-primary-new">Personal Information</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Name *</label>
                                    <input type="text" class="form-control"  name="first_name" value="{{ old('first_name', $customer->name ?? '') }}"
                                         {{ $customer ? 'readonly' : '' }} required >
                                        
                                    @error('first_name')
                                      <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email *</label>
                                     <input type="email" class="form-control" name="email" 
                                        value="{{ old('email', $customer->email ?? '') }}" 
                                        {{ $customer ? 'readonly' : '' }} required>
                                    @error('email')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Mobile Number *</label>
                                    <input type="text" class="form-control" name="phone" 
                                        value="{{ old('phone', $customer->phone ?? '') }}" required maxlength="10">
                                       
                                    @error('phone')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <hr class="my-4">

                            <h6 class="mb-3 text-primary-new">Shipping Address</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Pin Code *</label>
                                    <input type="text" class="form-control" name="pincode" 
                                        id="checkoutPincode" value="{{ old('pincode') }}" required maxlength="6">
                                    
                            
                                    
                                    <div class="form-text" id="pincodeStatus"></div>
                                    @error('pincode')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">City *</label>
                                    <input type="text" class="form-control" name="city" value="{{ old('city') }}" readonly>
                                    @error('city')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">State *</label>
                                    <input type="text" class="form-control" name="state" 
                                        value="{{ old('state') }}" 
                                        placeholder="Enter state" 
                                        readonly>
                                    @error('state')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                

                                <div class="col-md-6">
                                    <label class="form-label">Country *</label>
                                    <input type="text" class="form-control" name="country" value="India" readonly>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Street Address *</label>
                                    <input type="text" class="form-control" name="address" 
                                        placeholder="House number and street name" value="{{ old('address') }}" required>
                                    @error('address')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Apartment, Suite, etc. (optional)</label>
                                    <input type="text" class="form-control" name="address2" value="{{ old('address2') }}">
                                </div>

                                
                            </div>

                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" id="shipDifferent">
                                <label class="form-check-label" for="shipDifferent">
                                    <strong>Ship to a different address?</strong>
                                </label>
                            </div>

                            <div id="differentAddress" style="display:none;" class="mt-3">
                                <h6 class="mb-3 text-primary-new">Different Shipping Address</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">First Name</label>
                                        <input type="text" class="form-control" name="shipping_first_name" value="{{ old('shipping_first_name') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Last Name</label>
                                        <input type="text" class="form-control" name="shipping_last_name" value="{{ old('shipping_last_name') }}">
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Address</label>
                                        <input type="text" class="form-control" name="shipping_address" value="{{ old('shipping_address') }}">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">City</label>
                                        <input type="text" class="form-control" name="shipping_city" value="{{ old('shipping_city') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">State</label>
                                        <input type="text" class="form-control" name="shipping_state" value="{{ old('shipping_state') }}">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Pincode</label>
                                        <input type="text" class="form-control" name="shipping_pincode" value="{{ old('shipping_pincode') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Phone No</label>
                                        <input type="text" class="form-control" name="shipping_phone" value="{{ old('shipping_phone') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="button" class="btn btn-primary btn-lg w-100" id="nextToStep2">
                                    Continue to Order Summary
                                </button>
                            </div>
                        </div>
                    </div>

                   
                    <div class="col-lg-6">
                        <div class="checkout-box">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="section-title mb-0">Your Order Items</h4>
                                <span class="badge bg-primary-new">{{ $itemCount }} Item{{ $itemCount > 1 ? 's' : '' }}</span>
                            </div>

                            
                            <div class="order-items-preview">
                                @foreach($cartItems as $item)
                                <div class="order-item-preview border-bottom pb-3 mb-3">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-shrink-0">
                                            <img src="{{ url('userassets/image/product/' . ($item->image ?? 'placeholder.png')) }}" 
                                                alt="{{ $item->name }}" 
                                                class="rounded" 
                                                style="width: 70px; height: 70px; object-fit: cover;object-position: top;">
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-1 product-dotss">{{ $item->name }}</h6>
                                            
                                            @if($item->sku)
                                                <small class="text-muted d-block">ISBN: {{ $item->sku }}</small>
                                            @endif
                                            
                                            <div class="d-flex flex-wrap gap-2 mt-2">
                                               
                                                
                                                <span class="badge bg-light text-dark small">
                                                    <i class="bi bi-box me-1"></i>Qty: {{ $item->qty }}
                                                </span>
                                                
                                               
    <button type="button" class="btn btn-sm btn-outline-danger delete-cart-item" 
            data-id="{{ $item->id }}" title="Remove item">
        <i class="bi bi-trash3"></i>
    </button>

                                            </div>
                                            
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-bold text-primary-new">₹{{ number_format($item->price * $item->qty, 2) }}</div>
                                            @if($item->mrp_price > $item->price)
                                            <small class="text-muted text-decoration-line-through">
                                                ₹{{ number_format($item->mrp_price * $item->qty, 2) }}
                                            </small>
                                            <small class="text-success d-block small">
                                                Save ₹{{ number_format(($item->mrp_price - $item->price) * $item->qty, 2) }}
                                            </small>
                                            @endif
                                            <!-- Existing item div ke andar, text-end div ke baad ye add karein -->

                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                           
                            <div class="quick-summary mt-4 p-3 rounded" style="background: #f8f9fa;">
                                <h6 class="mb-3">Quick Summary</h6>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Items Total:</span>
                                    <span>₹{{ number_format($finalTotal, 2) }}</span>
                                </div>
                                
                                @if($couponDiscount > 0)
                                <div class="d-flex justify-content-between mb-2 text-success">
                                    <span>Coupon Discount:</span>
                                    <span>-₹{{ number_format($couponDiscount, 2) }}</span>
                                </div>
                                @endif
                                
                             
                                <div class="d-flex justify-content-between mb-2">
    <span>Shipping:</span>
    <span>
        @if($totalShipping == 0)
            <span class="text-success fw-bold">FREE</span>
        @else
            ₹{{ number_format($totalShipping, 2) }}
        @endif
    </span>
</div>
                                <hr>
                                <div class="d-flex justify-content-between fw-bold">
                                    <span>Estimated Total:</span>
                                    <span class="text-primary-new">₹{{ number_format($grandTotal, 2) }}</span>
                                </div>

                                @if($couponDiscount > 0)
                                <div class="alert alert-success mt-3 py-2">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-tag-fill me-2"></i>
                                        <small>
                                            <strong>Coupon Applied!</strong> 
                                            Saved ₹{{ number_format($couponDiscount, 2) }} with {{ session('applied_coupon.code') }}
                                        </small>
                                    </div>
                                </div>
                                @endif
                            </div>

                            
                            <div class="help-section mt-4">
                                <div class="card border-0" style="background: #f8f9fa;">
                                    <div class="card-body text-center">
                                        <i class="bi bi-question-circle display-4 text-primary-new mb-3"></i>
                                        <h6>Need Help?</h6>
                                        <p class="small text-muted mb-2">Our team is here to assist you</p>
                                        <div class="fw-bold text-primary-new"><i class="bi bi-telephone"></i> {{ $setting->phone ?? '' }}</div>
                                        <small class="text-muted">Mon-Sun, 9AM-10PM</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="checkout-step" id="step2" style="display: none;">
                <div class="row g-4">
                  
                    <div class="col-md-7">
                        <div class="checkout-box">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="section-title mb-0">Order Summary</h4>
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="backToStep1">
                                    <i class="bi bi-arrow-left me-1"></i>Edit Information
                                </button>
                            </div>

                          
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="bi bi-person-check me-2"></i>
                                        Personal Information
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <strong>Name:</strong>
                                            <p class="mb-1" id="previewName">{{ auth()->user()->first_name ?? '' }} {{ auth()->user()->last_name ?? '' }}</p>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <strong>Phone:</strong>
                                            <p class="mb-1" id="previewPhone">{{ auth()->user()->phone ?? '' }}</p>
                                        </div>
                                        <div class="col-12 mb-2">
                                            <strong>Email:</strong>
                                            <p class="mb-1" id="previewEmail">{{ auth()->user()->email ?? '' }}</p>
                                        </div>
                                        <div class="col-12">
                                            <strong>Address:</strong>
                                            <p class="mb-0" id="previewAddress">
                                                {{ old('address') ?? '' }}, 
                                                {{ old('city') ?? '' }}, 
                                                {{ old('state') ?? '' }} - {{ old('pincode') ?? '' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                          
                            <div class="order-items mb-4">
                                <h6 class="text-primary-new mb-3">Order Items ({{ $itemCount }})</h6>
                                
                                @foreach($cartItems as $item)
                                <div class="product-row border-bottom pb-3 mb-3">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <img src="{{ url('userassets/image/product/' . ($item->image ?? 'placeholder.png')) }}" 
                                                alt="{{ $item->name }}" 
                                                style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-1">{{ $item->product->name }}</h6>
                                            
                                            @if($item->product && $item->product->sku)
                                            <small class="text-muted d-block">ISBN: {{ $item->product->sku }}</small>
                                            @endif
                                            
                                            <div class="d-flex flex-wrap gap-2 mt-2">
                                               
                                                
                                                <span class="badge bg-light text-dark">
                                                    <i class="bi bi-box me-1"></i>Qty: {{ $item->qty }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-bold text-primary-new">₹{{ number_format($item->price * $item->qty, 2) }}</div>
                                            @if($item->mrp_price > $item->price)
                                            <small class="text-muted text-decoration-line-through">
                                                ₹{{ number_format($item->mrp_price * $item->qty, 2) }}
                                            </small>
                                            <small class="text-success d-block">
                                                Save ₹{{ number_format(($item->mrp_price - $item->price) * $item->qty, 2) }}
                                            </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                           
                            <div class="price-breakdown">
                                <h6 class="text-primary-new mb-3">Price Details</h6>
                                
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Total MRP</span>
                                    <span>₹{{ number_format($totalMRP, 2) }}</span>
                                </div>
                                
                                <div class="d-flex justify-content-between mb-2 ">
                                    <span>Discount on MRP</span>
                                    <span class="text-success">-₹{{ number_format($totalDiscountAmount, 2) }}</span>
                                </div>

                                @if($couponDiscount > 0)
                                <div class="d-flex justify-content-between mb-2 text-success">
                                    <span>Coupon Discount</span>
                                    <span>-₹{{ number_format($couponDiscount, 2) }}</span>
                                </div>
                                @endif

                              
                                <div class="d-flex justify-content-between mb-2">
    <span>Shipping</span>
    <span>
        @if($totalShipping == 0)
            <span class="text-success">FREE</span>
        @else
            ₹{{ number_format($totalShipping, 2) }}
        @endif
    </span>
</div>
                                <hr>

                                <div class="d-flex justify-content-between mb-2 fw-bold fs-5">
                                    <span>Total Amount</span>
                                    <span class="text-primary-new">₹{{ number_format($grandTotal, 2) }}</span>
                                </div>

                                @if($couponDiscount > 0)
                                <div class="alert alert-success mt-3">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-tag-fill me-2"></i>
                                        <div>
                                            <strong>Coupon Applied!</strong>
                                            <div class="small">You saved ₹{{ number_format($couponDiscount, 2) }} with code: {{ session('applied_coupon.code') }}</div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                   
                    <div class="col-md-5">
                        <div class="checkout-box">
                            <h4 class="section-title">Payment Method</h4>

                            <div class="payment-methods">
                                <!-- @php
                                    
                                    $paymentMethods = \App\Models\PaymentMethod::where('status', 1)->get();
                                @endphp
                                
                                @foreach($paymentMethods as $method)
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="payment_method_id" 
                                            id="payment_{{ $method->id }}" value="{{ $method->id }}"
                                            {{ $loop->first ? 'checked' : '' }}>
                                        <label class="form-check-label" for="payment_{{ $method->id }}">
                                            <strong>{{ $method->payment_type }}</strong><br>
                                            <small class="text-muted">{{ $method->description ?? '' }}</small>
                                        </label>
                                    </div>
                                @endforeach -->
                                @php
                                    
                                    $paymentMethods = \App\Models\PaymentMethod::where('status', 1)->get();
                                @endphp
                                @php
    $allCodAvailable = $cartItems->every(function($item) {
        return optional($item->product)->cod_available == 1;
    });
@endphp

@foreach($paymentMethods as $method)
    @if($method->isCOD() && !$allCodAvailable)
        @continue
    @endif
    <div class="form-check mb-3">
        <input class="form-check-input" type="radio" name="payment_method_id"
            id="payment_{{ $method->id }}" value="{{ $method->id }}"
            {{ $loop->first ? 'checked' : '' }}>
        <label class="form-check-label" for="payment_{{ $method->id }}">
            <strong>{{ $method->payment_type }}</strong><br>
            <small class="text-muted">{{ $method->description ?? '' }}</small>
        </label>
    </div>
@endforeach
                            </div>

                           
                            <div class="card mt-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="bi bi-receipt me-2"></i>
                                        Order Total
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Items ({{ $itemCount }}):</span>
                                        <span>₹{{ number_format($finalTotal, 2) }}</span>
                                    </div>
                                    
                                    @if($couponDiscount > 0)
                                    <div class="d-flex justify-content-between mb-2 text-success">
                                        <span>Coupon Discount:</span>
                                        <span>-₹{{ number_format($couponDiscount, 2) }}</span>
                                    </div>
                                    @endif
                                    
                                 
                                    <div class="d-flex justify-content-between mb-2">
    <span>Shipping:</span>
    <span>
        @if($totalShipping == 0)
            <span class="text-success fw-bold">FREE</span>
        @else
            ₹{{ number_format($totalShipping, 2) }}
        @endif
    </span>
</div>
                                    <hr>
                                    <div class="d-flex justify-content-between fw-bold fs-5">
                                        <span>Grand Total:</span>
                                        <span class="text-primary-new">₹{{ number_format($grandTotal, 2) }}</span>
                                    </div>
                                </div>
                            </div>

                           
                            <input type="hidden" name="coupon_code" value="{{ session('applied_coupon.code') ?? '' }}">
                            <input type="hidden" name="coupon_discount" value="{{ $couponDiscount }}">

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary btn-lg w-100" id="placeOrderBtn">
                                    <i class="bi bi-check-circle me-2"></i>Place Order
                                </button>
                            </div>

                            <div class="text-center mt-3">
                                <small class="text-muted">
                                    By placing your order, you agree to our 
                                    <a href="#">Terms & Conditions</a> and <a href="#">Privacy Policy</a>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

@include('userfooter')

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    const nextToStep2 = document.getElementById('nextToStep2');
    const backToStep1 = document.getElementById('backToStep1');
    const checkoutForm = document.getElementById('checkoutForm');
    const checkoutSteps = document.querySelectorAll('.checkout-steps span');
    const placeOrderBtn = document.getElementById('placeOrderBtn');
    const shipDifferentCheckbox = document.getElementById('shipDifferent');
    const differentAddressSection = document.getElementById('differentAddress');
    const checkoutPincodeInput = document.getElementById('checkoutPincode');
    const pincodeStatus = document.getElementById('pincodeStatus');
    const cityInput = document.querySelector('input[name="city"]');
    const stateInput = document.querySelector('input[name="state"]');
    
    let step1Validated = false;
    let currentPincode = '';
    let isPincodeValidated = false;

   
    nextToStep2.addEventListener('click', function(e) {
        e.preventDefault();
        
       
        if (validateStep1()) {
            
            if (!isPincodeValidated) {
                showNotification('Please validate your pincode before proceeding', 'error');
                checkoutPincodeInput.focus();
                return;
            }
            
            step1Validated = true;
            updatePreviewInformation();
            
            
            step1.style.opacity = '0';
            step1.style.transform = 'translateX(-20px)';
            
            setTimeout(() => {
                step1.style.display = 'none';
                step2.style.display = 'block';
                step2.style.opacity = '0';
                step2.style.transform = 'translateX(20px)';
                
                setTimeout(() => {
                    step2.style.opacity = '1';
                    step2.style.transform = 'translateX(0)';
                    
                    // Update steps
                    checkoutSteps[0].classList.remove('active');
                    checkoutSteps[1].classList.add('active');
                    
                    // Scroll to top of step2
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }, 50);
            }, 300);
        }
    });

    backToStep1.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Animate step transition
        step2.style.opacity = '0';
        step2.style.transform = 'translateX(20px)';
        
        setTimeout(() => {
            step2.style.display = 'none';
            step1.style.display = 'block';
            step1.style.opacity = '0';
            step1.style.transform = 'translateX(-20px)';
            
            setTimeout(() => {
                step1.style.opacity = '1';
                step1.style.transform = 'translateX(0)';
                
                // Update steps
                checkoutSteps[1].classList.remove('active');
                checkoutSteps[0].classList.add('active');
                
                // Scroll to top of step1
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }, 50);
        }, 300);
    });

    // Different shipping address toggle
    if (shipDifferentCheckbox) {
        shipDifferentCheckbox.addEventListener("change", function() {
            if (this.checked) {
                differentAddressSection.style.display = "block";
                // Smooth animation
                setTimeout(() => {
                    differentAddressSection.style.opacity = '1';
                    differentAddressSection.style.transform = 'translateY(0)';
                }, 10);
            } else {
                differentAddressSection.style.opacity = '0';
                differentAddressSection.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    differentAddressSection.style.display = "none";
                }, 300);
            }
        });
    }

    
    // Function to fetch city and state from pincode
    async function fetchCityState(pincode) {
        try {
            showLoadingInPincodeField(true);
            
            const response = await fetch('/customer/fetch-city-state', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ pincode: pincode })
            });
            
            const data = await response.json();
            return data;
            
        } catch (error) {
            console.error('Error fetching city/state:', error);
            return { 
                success: false, 
                message: 'Network error. Please check your connection.' 
            };
        } finally {
            showLoadingInPincodeField(false);
        }
    }

    // Function to show loading in pincode field
    function showLoadingInPincodeField(show) {
        if (show) {
            pincodeStatus.innerHTML = '<span class="text-warning">⏳ Checking pincode availability...</span>';
        }
    }

    // Function to make city and state fields readonly
    function makeCityStateReadonly() {
        if (cityInput) {
            cityInput.setAttribute('readonly', true);
            cityInput.style.backgroundColor = '#f8f9fa';
            cityInput.style.cursor = 'default';
        }
        
        if (stateInput) {
            stateInput.setAttribute('readonly', true);
            stateInput.style.backgroundColor = '#f8f9fa';
            stateInput.style.cursor = 'default';
            
            // For select dropdown, also disable options
           // stateInput.disabled = true;
        }
    }

    // Function to enable city and state fields for editing
    function enableCityStateFields() {
        if (cityInput) {
            cityInput.removeAttribute('readonly');
            cityInput.style.backgroundColor = '';
            cityInput.style.cursor = '';
        }
        
        if (stateInput) {
            stateInput.removeAttribute('readonly');
            stateInput.style.backgroundColor = '';
            stateInput.style.cursor = '';
           // stateInput.disabled = false;
        }
    }

    // Debounce function to limit API calls
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

   
    // Modify the validateAndAutoFillPincode function to clear localStorage when pincode is changed
async function validateAndAutoFillPincode() {
    const pincode = checkoutPincodeInput.value.trim();
    currentPincode = pincode;
    
    // Enable fields if pincode is being changed
    if (pincode.length < 6) {
        enableCityStateFields();
        clearCityStateFields();
        isPincodeValidated = false;
        checkoutPincodeInput.classList.remove('is-valid', 'is-invalid');
        pincodeStatus.innerHTML = '';
        
        // Clear localStorage if pincode is being cleared
        if (pincode.length === 0) {
            localStorage.removeItem('verifiedPincode');
            localStorage.removeItem('pincodeCity');
            localStorage.removeItem('pincodeState');
            localStorage.removeItem('pincodeCheckTime');
        }
        return;
    }
    
    if (pincode.length === 6 && /^\d+$/.test(pincode)) {
        try {
            const result = await fetchCityState(pincode);
            
            if (result.success && result.delivery_available) {
                // Pincode is available for delivery
                checkoutPincodeInput.classList.add('is-valid');
                checkoutPincodeInput.classList.remove('is-invalid');
                isPincodeValidated = true;
                
                // Auto-fill city if available
                if (result.city && cityInput) {
                    cityInput.value = result.city;
                    cityInput.classList.add('is-valid');
                    cityInput.classList.remove('is-invalid');
                    makeCityStateReadonly();
                }
                 if (result.state && stateInput) {
                    stateInput.value = result.state;
                    stateInput.classList.add('is-valid');
                    stateInput.classList.remove('is-invalid');
                    makeCityStateReadonly();
                }
              
                // Show success message
                if (result.city && result.state) {
                    pincodeStatus.innerHTML = `<span class="text-success">
                        <i class="bi bi-check-circle-fill"></i> Delivery available! 
                        City: <strong>${result.city}</strong>, 
                        State: <strong>${result.state}</strong>
                    </span>`;
                    
                    showNotification('Pincode verified! City and state auto-filled.', 'success');
                } else if (result.message) {
                    pincodeStatus.innerHTML = `<span class="text-success">
                        <i class="bi bi-check-circle-fill"></i> ${result.message}
                    </span>`;
                    
                    // If city/state not available from API, keep fields editable
                    if (!result.city || !result.state) {
                        showNotification('Please enter city and state manually', 'info');
                    }
                }
                
                // Store in localStorage for future use
                localStorage.setItem('verifiedPincode', pincode);
                localStorage.setItem('pincodeCity', result.city || '');
                localStorage.setItem('pincodeState', result.state || '');
                localStorage.setItem('pincodeCheckTime', new Date().getTime());
                
            } else {
                // Pincode not available
                checkoutPincodeInput.classList.add('is-invalid');
                checkoutPincodeInput.classList.remove('is-valid');
                isPincodeValidated = false;
                
                pincodeStatus.innerHTML = `<span class="text-danger">
                    <i class="bi bi-x-circle-fill"></i> ${result.message || 'Delivery not available'}
                </span>`;
                
                // Clear city and state fields and make them editable
                clearCityStateFields();
                enableCityStateFields();
                
                // Clear localStorage for invalid pincode
                localStorage.removeItem('verifiedPincode');
                localStorage.removeItem('pincodeCity');
                localStorage.removeItem('pincodeState');
                localStorage.removeItem('pincodeCheckTime');
                
                // Show error notification
                showNotification(result.message || 'Delivery not available at this pincode', 'error');
            }
            
        } catch (error) {
            console.error('Pincode validation error:', error);
            pincodeStatus.innerHTML = `<span class="text-danger">
                <i class="bi bi-exclamation-triangle-fill"></i> Error checking pincode. Please try again.
            </span>`;
            isPincodeValidated = false;
            enableCityStateFields();
        }
        
    } else if (pincode.length > 0) {
        pincodeStatus.innerHTML = `<span class="text-danger">
            <i class="bi bi-x-circle-fill"></i> Please enter a valid 6-digit pincode
        </span>`;
        checkoutPincodeInput.classList.add('is-invalid');
        checkoutPincodeInput.classList.remove('is-valid');
        isPincodeValidated = false;
        
        clearCityStateFields();
        enableCityStateFields();
    } else {
        pincodeStatus.innerHTML = '';
        checkoutPincodeInput.classList.remove('is-invalid', 'is-valid');
        isPincodeValidated = false;
        enableCityStateFields();
    }
}


    // Debounced version of validateAndAutoFillPincode
    const debouncedValidatePincode = debounce(validateAndAutoFillPincode, 800);

    // Function to clear city and state fields
    function clearCityStateFields() {
        if (cityInput) {
            cityInput.value = '';
            cityInput.classList.remove('is-valid');
            cityInput.classList.add('is-invalid');
        }
        if (stateInput) {
            stateInput.value = '';
            stateInput.classList.remove('is-valid');
            stateInput.classList.add('is-invalid');
        }
       
    }

    // Pincode event listeners
    if (checkoutPincodeInput) {
        // Real-time validation on input (6 digits complete)
        checkoutPincodeInput.addEventListener('input', function() {
            const pincode = this.value.trim();
            this.value = this.value.replace(/\D/g, '').slice(0, 6);
            
            // Clear any previous status
            pincodeStatus.innerHTML = '';
            this.classList.remove('is-invalid', 'is-valid');
            
            // If 6 digits entered, trigger validation
            if (pincode.length === 6 && /^\d+$/.test(pincode)) {
                debouncedValidatePincode();
            } else {
                isPincodeValidated = false;
                enableCityStateFields();
                clearCityStateFields();
            }
        });
        
        // Also validate on blur (backup)
        checkoutPincodeInput.addEventListener('blur', function() {
            const pincode = this.value.trim();
            if (pincode.length === 6 && /^\d+$/.test(pincode) && !isPincodeValidated) {
                validateAndAutoFillPincode();
            }
        });
        
        // Validate when Enter key is pressed
        checkoutPincodeInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                validateAndAutoFillPincode();
            }
        });
        
        // Auto-focus and validate if pincode is already in localStorage
        window.addEventListener('load', function() {
            const storedPincode = localStorage.getItem('verifiedPincode');
            const storedCity = localStorage.getItem('pincodeCity');
            const storedState = localStorage.getItem('pincodeState');
            const storedTime = localStorage.getItem('pincodeCheckTime');
            
            if (storedPincode && storedTime) {
                const currentTime = new Date().getTime();
                const oneHour = 60 * 60 * 1000;
                
                // If pincode is less than 1 hour old and field is empty
                if ((currentTime - parseInt(storedTime)) < oneHour && !checkoutPincodeInput.value) {
                    checkoutPincodeInput.value = storedPincode;
                    
                    // Pre-fill city if available
                    if (storedCity && cityInput && !cityInput.value) {
                        cityInput.value = storedCity;
                        cityInput.classList.add('is-valid');
                    }
                    
                    // Pre-fill state if available
                    if (storedState && stateSelect && !stateSelect.value) {
                        stateSelect.value = storedState;
                        stateSelect.classList.add('is-valid');
                    }
                    
                    // If both city and state are available, make them readonly
                    if (storedCity && storedState) {
                        makeCityStateReadonly();
                        isPincodeValidated = true;
                        
                        pincodeStatus.innerHTML = `<span class="text-success">
                            <i class="bi bi-check-circle-fill"></i> Using saved pincode data
                        </span>`;
                    }
                    
                    // Auto-validate after a short delay
                    setTimeout(() => {
                        if (checkoutPincodeInput.value === storedPincode) {
                            validateAndAutoFillPincode();
                        }
                    }, 1000);
                }
            }
        });
    }

    // City and state manual entry validation (only if they are editable)
    if (cityInput) {
        cityInput.addEventListener('input', function() {
            if (!this.hasAttribute('readonly') && this.value.trim()) {
                this.classList.add('is-valid');
                this.classList.remove('is-invalid');
            }
        });
    }
    if (stateInput) {
        stateInput.addEventListener('input', function() {
            if (!this.hasAttribute('readonly') && this.value.trim()) {
                this.classList.add('is-valid');
                this.classList.remove('is-invalid');
            }
        });
    }
   

// Function to restore pincode and city/state from localStorage on page load
function restorePincodeData() {
    const storedPincode = localStorage.getItem('verifiedPincode');
    const storedCity = localStorage.getItem('pincodeCity');
    const storedState = localStorage.getItem('pincodeState');
    const storedTime = localStorage.getItem('pincodeCheckTime');
    
    if (storedPincode && storedTime) {
        const currentTime = new Date().getTime();
        const oneHour = 60 * 60 * 1000;
        
        // If pincode is less than 1 hour old
        if ((currentTime - parseInt(storedTime)) < oneHour) {
            // Set pincode value
            if (checkoutPincodeInput && !checkoutPincodeInput.value) {
                checkoutPincodeInput.value = storedPincode;
                checkoutPincodeInput.classList.add('is-valid');
                isPincodeValidated = true;
            }
            
            // Set city value
            if (storedCity && cityInput && !cityInput.value) {
                cityInput.value = storedCity;
                cityInput.classList.add('is-valid');
            }
            
            // Set state value
            if (storedState && stateInput && !stateInput.value) {
                stateInput.value = storedState;
                stateInput.classList.add('is-valid');
            }
            
            // Make city and state readonly if both are available
            if (storedCity && storedState) {
                makeCityStateReadonly();
                
                pincodeStatus.innerHTML = `<span class="text-success">
                    <i class="bi bi-check-circle-fill"></i> Using saved pincode data
                </span>`;
            }
        } else {
            // Clear expired data
            localStorage.removeItem('verifiedPincode');
            localStorage.removeItem('pincodeCity');
            localStorage.removeItem('pincodeState');
            localStorage.removeItem('pincodeCheckTime');
        }
    }
}

// Modify the validateStep1 function to handle readonly fields
function validateStep1() {
    let isValid = true;
    const requiredFields = step1.querySelectorAll('input[required], select[required]');
    
    // Reset all invalid states first (except readonly valid fields)
    requiredFields.forEach(field => {
        if (!field.classList.contains('is-valid') || !field.hasAttribute('readonly')) {
            field.classList.remove('is-invalid', 'is-valid');
        }
    });
    
    // Special pincode validation
    if (checkoutPincodeInput) {
        const pincodeValue = checkoutPincodeInput.value.trim();
        if (!pincodeValue || pincodeValue.length !== 6 || !/^\d+$/.test(pincodeValue)) {
            checkoutPincodeInput.classList.add('is-invalid');
            checkoutPincodeInput.classList.remove('is-valid');
            isValid = false;
        } else if (!isPincodeValidated) {
            checkoutPincodeInput.classList.add('is-invalid');
            checkoutPincodeInput.classList.remove('is-valid');
            pincodeStatus.innerHTML = `<span class="text-danger">
                <i class="bi bi-exclamation-triangle-fill"></i> Please validate pincode
            </span>`;
            isValid = false;
        }
    }
    
    // Validate each field
    requiredFields.forEach(field => {
        // Skip readonly fields that already have values
        if (field.hasAttribute('readonly') && field.value.trim()) {
            return; // Skip validation for readonly fields with values
        }
        
        const value = field.value.trim();
        
        if (!value) {
            field.classList.add('is-invalid');
            field.classList.remove('is-valid');
            isValid = false;
            return;
        }
        
        // Field-specific validations
        if (field.name === 'email') {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                field.classList.add('is-invalid');
                field.classList.remove('is-valid');
                isValid = false;
                return;
            }
        }
        
        if (field.name === 'phone') {
            if (value.length !== 10 || !/^\d+$/.test(value)) {
                field.classList.add('is-invalid');
                field.classList.remove('is-valid');
                isValid = false;
                return;
            }
        }
        
        if (field.name === 'state' && value === "") {
            field.classList.add('is-invalid');
            field.classList.remove('is-valid');
            isValid = false;
            return;
        }
        
        // If passed all validations
        field.classList.add('is-valid');
    });
    
    if (!isValid) {
        // Show error notification
        showNotification('Please fill all required fields correctly', 'error');
        
        // Find first invalid field and scroll to it
        const firstInvalid = step1.querySelector('.is-invalid');
        if (firstInvalid) {
            firstInvalid.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'center' 
            });
            firstInvalid.focus();
        }
    }
    
    return isValid;
}

    // Update preview information
    function updatePreviewInformation() {
        const firstName = document.querySelector('input[name="first_name"]').value;
        const email = document.querySelector('input[name="email"]').value;
        const phone = document.querySelector('input[name="phone"]').value;
        const address = document.querySelector('input[name="address"]').value;
        const address2 = document.querySelector('input[name="address2"]').value;
        const city = document.querySelector('input[name="city"]').value;
        const state = document.querySelector('input[name="state"]').value;
        const pincode = document.querySelector('input[name="pincode"]').value;

        document.getElementById('previewName').textContent = firstName;
        document.getElementById('previewEmail').textContent = email;
        document.getElementById('previewPhone').textContent = phone;
        
        let addressText = address;
        if (address2) addressText += `, ${address2}`;
        addressText += `, ${city}, ${state} - ${pincode}`;
        
        document.getElementById('previewAddress').textContent = addressText;
    }

    // Real-time validation for fields
    function setupRealTimeValidation() {
        const fields = step1.querySelectorAll('input, select');
        
        fields.forEach(field => {
            field.addEventListener('blur', function() {
                validateSingleField(this);
            });
            
            field.addEventListener('input', function() {
                if (this.classList.contains('is-invalid')) {
                    validateSingleField(this);
                }
            });
            
            // Phone number formatting
            if (field.name === 'phone') {
                field.addEventListener('input', function() {
                    this.value = this.value.replace(/\D/g, '').slice(0, 10);
                });
            }
        });
    }

    function validateSingleField(field) {
        const value = field.value.trim();
        
        if (field.hasAttribute('required') && !value) {
            field.classList.add('is-invalid');
            field.classList.remove('is-valid');
            return false;
        }
        
        // Field-specific validations
        if (field.name === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                field.classList.add('is-invalid');
                field.classList.remove('is-valid');
                return false;
            }
        }
        
        if (field.name === 'phone' && value) {
            if (value.length !== 10 || !/^\d+$/.test(value)) {
                field.classList.add('is-invalid');
                field.classList.remove('is-valid');
                return false;
            }
        }
        
        if (field.name === 'state' && field.value === "" && field.hasAttribute('required')) {
            field.classList.add('is-invalid');
            field.classList.remove('is-valid');
            return false;
        }
        
        // If valid
        field.classList.remove('is-invalid');
        field.classList.add('is-valid');
        return true;
    }

    // Form Submission
   // checkout.blade.php में form submission handler को अपडेट करें
checkoutForm.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    // Check if step1 is validated
    if (!step1Validated) {
        showNotification('Please complete your personal information first', 'error');
        backToStep1.click();
        return;
    }
    
    // Final validation of step1 fields
    if (!validateStep1()) {
        showNotification('Please fix the errors in personal information', 'error');
        backToStep1.click();
        return;
    }
    
    // Pincode validation check
    if (!isPincodeValidated) {
        showNotification('Please validate your pincode before placing order', 'error');
        checkoutPincodeInput.focus();
        return;
    }
    
    // Get selected payment method
    const paymentMethodIdInput = document.querySelector('input[name="payment_method_id"]:checked');
    if (!paymentMethodIdInput) {
        showNotification('Please select a payment method', 'error');
        return;
    }
    
    // Check if it's COD or Online (यहाँ हम payment method ID के आधार पर check करेंगे)
    const paymentMethodId = paymentMethodIdInput.value;
    const isCOD = paymentMethodId == 2; // यहाँ assumption है कि COD का ID 2 है
    
    // Show loading
    const originalText = placeOrderBtn.innerHTML;
    placeOrderBtn.disabled = true;
    placeOrderBtn.innerHTML = `
        <span class="spinner-border spinner-border-sm me-2"></span>
        Processing Your Order...
    `;
    
    // Prepare form data
    const formData = new FormData(this);
    
    try {
        // AJAX request
        const response = await fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        });
        
        // First check if response is OK
        if (!response.ok) {
            // Try to get error message from response
            let errorText = 'Server error: ' + response.status;
            try {
                const errorData = await response.json();
                errorText = errorData.error || errorData.message || errorText;
            } catch (e) {
                // If can't parse JSON, use status text
                errorText = response.statusText || errorText;
            }
            throw new Error(errorText);
        }
        
        // Try to parse JSON
        const data = await response.json();
        console.log('Server Response:', data);
        
        // Check if response has success property
        if (typeof data.success !== 'undefined') {
            if (data.success) {
                // Check if it's COD or Online payment
                if (data.is_cod) {
                    // COD - redirect to confirmation
                    window.location.href = data.redirect;
                } else if (data.is_online && data.razorpay_order_id) {
                    // Online payment - process Razorpay
                    await processRazorpayPayment(data);
                } else {
                    // Fallback: check payment method ID from form
                    if (isCOD) {
                        window.location.href = data.redirect;
                    } else {
                        throw new Error('Payment order creation failed');
                    }
                }
            } else {
                // Show validation errors if any
                if (data.errors) {
                    let errorMessage = '';
                    for (let field in data.errors) {
                        errorMessage += data.errors[field][0] + '\n';
                    }
                    throw new Error(errorMessage);
                } else {
                    throw new Error(data.error || data.message || 'Unknown error occurred');
                }
            }
        } else {
            // Check for Laravel validation errors (different format)
            if (data.message && data.errors) {
                let errorMessage = data.message + '\n';
                for (let field in data.errors) {
                    errorMessage += data.errors[field][0] + '\n';
                }
                throw new Error(errorMessage);
            } else {
                throw new Error('Invalid response format from server');
            }
        }
        
    } catch (error) {
        console.error('Order submission error:', error);
        
        // Reset button state
        placeOrderBtn.disabled = false;
        placeOrderBtn.innerHTML = originalText;
        
        // Show error message (shorten if too long)
        let errorMsg = error.message || 'Error placing order. Please try again.';
        if (errorMsg.length > 100) {
            errorMsg = errorMsg.substring(0, 100) + '...';
        }
        
        showNotification(errorMsg, 'error');
        
        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
});
    /**
 * Process Razorpay Payment Directly
 */

async function processRazorpayPayment(data) {
    try {
        const options = {
            key: data.razorpay_key,
            amount: data.amount,
            currency: "INR",
            name: "{{ env('APP_NAME', 'Your Store') }}",
            description: "Order #" + data.order_number,
            order_id: data.razorpay_order_id,
            handler: async function(response) {
                // Payment successful
                await verifyRazorpayPayment(response, data.order_number);
            },
            prefill: {
                name: data.customer_name,
                email: data.customer_email,
                contact: data.customer_phone
            },
            notes: {
                order_id: data.order_id,
                order_number: data.order_number
            },
            theme: {
                color: "#007bff"
            },
            modal: {
                ondismiss: function() {
                    // User closed the modal
                    showNotification('Payment cancelled. You can try again.', 'error');
                    placeOrderBtn.disabled = false;
                    placeOrderBtn.innerHTML = isCOD ? 
                        '<i class="bi bi-truck me-2"></i>Place COD Order' : 
                        '<i class="bi bi-credit-card me-2"></i>Proceed to Payment';
                }
            }
        };
        
        const rzp = new Razorpay(options);
        
        rzp.on('payment.failed', function(response) {
            console.error('Payment failed:', response.error);
            
            // Show error message
            showNotification('Payment failed: ' + (response.error.description || 'Unknown error'), 'error');
            
            // Reset button
            placeOrderBtn.disabled = false;
            placeOrderBtn.innerHTML = isCOD ? 
                '<i class="bi bi-truck me-2"></i>Place COD Order' : 
                '<i class="bi bi-credit-card me-2"></i>Proceed to Payment';
            
           
        });
        
        // Open Razorpay modal
        rzp.open();
        
    } catch (error) {
        console.error('Razorpay initialization error:', error);
        showNotification('Error initializing payment. Please try again.', 'error');
        placeOrderBtn.disabled = false;
        placeOrderBtn.innerHTML = isCOD ? 
            '<i class="bi bi-truck me-2"></i>Place COD Order' : 
            '<i class="bi bi-credit-card me-2"></i>Proceed to Payment';
    }
}

async function verifyRazorpayPayment(paymentResponse, orderNumber) { // Changed parameter name
    try {
        // Show verifying status
        placeOrderBtn.disabled = true;
        placeOrderBtn.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2"></span>
            Verifying Payment...
        `;
        
        // Create form data
        const formData = new FormData();
        formData.append('razorpay_payment_id', paymentResponse.razorpay_payment_id);
        formData.append('razorpay_order_id', paymentResponse.razorpay_order_id);
        formData.append('razorpay_signature', paymentResponse.razorpay_signature);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        
        try {
            // Make POST request to verify payment
            const verifyResponse = await fetch(`/customer/payment/success/${orderNumber}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (verifyResponse.ok) {
                try {
                    const data = await verifyResponse.json();
                    if (data.success) {
                        window.location.href = data.redirect;
                    } else {
                        throw new Error(data.error || 'Payment verification failed');
                    }
                } catch (jsonError) {
                    // If response is not JSON (might be redirect), check status
                    if (verifyResponse.redirected) {
                        window.location.href = verifyResponse.url;
                    } else {
                        throw new Error('Invalid response from server');
                    }
                }
            } else {
                // Try to get error message
                let errorText = await verifyResponse.text();
                try {
                    const errorData = JSON.parse(errorText);
                    throw new Error(errorData.error || errorData.message || 'Payment verification failed');
                } catch (e) {
                    throw new Error(errorText || 'Payment verification failed with status: ' + verifyResponse.status);
                }
            }
            
        } catch (postError) {
            console.log('POST verification failed, trying GET...', postError);
            
            // Option 2: GET request fallback
            const params = new URLSearchParams({
                razorpay_payment_id: paymentResponse.razorpay_payment_id,
                razorpay_order_id: paymentResponse.razorpay_order_id,
                razorpay_signature: paymentResponse.razorpay_signature,
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            });
            
            window.location.href = `/customer/payment/success/${orderNumber}?${params.toString()}`;
        }
        
    } catch (error) {
        console.error('Payment verification error:', error);
        showNotification('Payment verification failed: ' + error.message, 'error');
        
        // Reset button
        placeOrderBtn.disabled = false;
        placeOrderBtn.innerHTML = '<i class="bi bi-credit-card me-2"></i>Try Again';
    }
}

    // JavaScript में payment method change event
const paymentRadios = document.querySelectorAll('input[name="payment_method_id"]');
paymentRadios.forEach(radio => {
    radio.addEventListener('change', function() {
        updatePlaceOrderButton(this.value);
    });
});

// function updatePlaceOrderButton(paymentMethodId) {
//     // COD है या नहीं check करें (assume COD id is 2)
//     const isCOD = paymentMethodId == 2;
//     if (isCOD) {
//         placeOrderBtn.innerHTML = '<i class="bi bi-truck me-2"></i>Proceed to Payment';
//     } else {
//         placeOrderBtn.innerHTML = '<i class="bi bi-credit-card me-2"></i>Proceed to Payment';
//     }
// }
function updatePlaceOrderButton(paymentMethodId) {
    
    const isCOD = paymentMethodId == {{ \App\Models\PaymentMethod::where('payment_type', 'COD')->first()->id ?? 2 }};
    if (isCOD) {
        placeOrderBtn.innerHTML = '<i class="bi bi-truck me-2"></i>Place COD Order';
    } else {
        placeOrderBtn.innerHTML = '<i class="bi bi-credit-card me-2"></i>Proceed to Payment';
    }
}
// Global variable for isCOD (form submission के लिए)
let isCOD = false;

// Form submission से पहले isCOD set करें
checkoutForm.addEventListener('submit', function(e) {
    const paymentMethodIdInput = document.querySelector('input[name="payment_method_id"]:checked');
    if (paymentMethodIdInput) {
        isCOD = paymentMethodIdInput.value == 2;
    }
});

// Initialize button text
// document.addEventListener('DOMContentLoaded', function() {
//     const defaultPaymentMethod = document.querySelector('input[name="payment_method_id"]:checked');
//     if (defaultPaymentMethod) {
//         updatePlaceOrderButton(defaultPaymentMethod.value);
//         isCOD = defaultPaymentMethod.value == 2;
//     }
// });
document.addEventListener('DOMContentLoaded', function() {
    const defaultPaymentMethod = document.querySelector('input[name="payment_method_id"]:checked');
    if (defaultPaymentMethod) {
        updatePlaceOrderButton(defaultPaymentMethod.value);
        // Global variable for isCOD
        isCOD = defaultPaymentMethod.value == {{ \App\Models\PaymentMethod::where('payment_type', 'COD')->first()->id ?? 2 }};
    }
});
    // Notification system
    function showNotification(message, type = 'info') {
        // Remove existing notifications
        const existingNotifications = document.querySelectorAll('.custom-notification');
        existingNotifications.forEach(notification => notification.remove());
        
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `custom-notification alert alert-${type === 'error' ? 'danger' : 'success'} alert-dismissible fade show`;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            max-width: 500px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border: none;
            border-radius: 8px;
        `;
        
        const icon = type === 'error' ? '❌' : '✅';
        notification.innerHTML = `
            <div class="d-flex align-items-center">
                <span style="font-size: 1.2em; margin-right: 10px;">${icon}</span>
                <div class="flex-grow-1">${message}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 1000);
    }

    // Auto-save form data
    function setupAutoSave() {
        const formFields = checkoutForm.querySelectorAll('input, select');
        
        formFields.forEach(field => {
            // Load saved data
            const savedValue = localStorage.getItem(`checkout_${field.name}`);
            if (savedValue !== null && !field.value) {
                field.value = savedValue;
                validateSingleField(field);
            }
            
            // Save on change
            field.addEventListener('input', function() {
                localStorage.setItem(`checkout_${this.name}`, this.value);
            });
            
            field.addEventListener('change', function() {
                localStorage.setItem(`checkout_${this.name}`, this.value);
            });
        });
    }
    function init() {
    // Restore pincode data from localStorage
    restorePincodeData();
    
    setupRealTimeValidation();
    setupAutoSave();
    updatePlaceOrderButton('cod'); // Default payment method
    
    // Focus on first empty field
    setTimeout(() => {
        const firstEmptyField = step1.querySelector('input:not([readonly]):not([value]), select:not([readonly]):not([value])');
        if (firstEmptyField) {
            firstEmptyField.focus();
        }
    }, 500);
    
    console.log('Checkout form initialized');
}

    init();
});

// Helper function for pincode validation (optional separate function)
async function validatePincodeBeforeSubmit(pincode) {
    try {
        const response = await fetch('/customer/fetch-city-state', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ pincode: pincode })
        });
        
        const data = await response.json();
        return data;
        
    } catch (error) {
        console.error('Pincode validation error:', error);
        return { 
            success: false, 
            message: 'Network error' 
        };
    }
} </script>
<script>
    // Cart item delete handler
document.addEventListener('click', async function(e) {
    const btn = e.target.closest('.delete-cart-item');
    if (!btn) return;
    
    const itemId = btn.dataset.id;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
    
    try {
        const response = await fetch(`/customer/cart/delete/${itemId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Remove item from DOM
            const itemRow = btn.closest('.order-item-preview');
            itemRow.style.transition = 'all 0.3s ease';
            itemRow.style.opacity = '0';
            itemRow.style.maxHeight = itemRow.offsetHeight + 'px';
            
            setTimeout(() => {
                itemRow.style.maxHeight = '0';
                itemRow.style.padding = '0';
                itemRow.style.margin = '0';
                itemRow.style.overflow = 'hidden';
                setTimeout(() => {
                    itemRow.remove();
                    updateCartTotals(data);
                }, 300);
            }, 100);
            
            showNotification('Item removed from cart', 'success');
            
            // Agar cart empty ho jaye to redirect
            if (data.cart_empty) {
                setTimeout(() => {
                    window.location.href = '/cart';
                }, 1500);
            }
        } else {
            showNotification(data.message || 'Error removing item', 'error');
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-trash3"></i>';
        }
    } catch (error) {
        showNotification('Network error. Please try again.', 'error');
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-trash3"></i>';
    }
});

function updateCartTotals(data) {
    // Item count badge update
    const badge = document.querySelector('.badge.bg-primary-new');
    if (badge && data.item_count !== undefined) {
        badge.textContent = data.item_count + ' Item' + (data.item_count !== 1 ? 's' : '');
    }
    
    // Summary totals update
    if (data.totals) {
        const t = data.totals;
        // Quick summary section
        document.querySelectorAll('.quick-summary .d-flex span:last-child').forEach((el, i) => {
            if (i === 0) el.textContent = '₹' + t.final_total.toFixed(2);
        });
        // Grand total
        document.querySelectorAll('.quick-summary .fw-bold span:last-child').forEach(el => {
            el.textContent = '₹' + t.grand_total.toFixed(2);
        });
    }
}
    </script>
<style>
.checkout-steps {
    margin: 30px 0;
    padding: 0 20px;
}

.checkout-steps span {
    padding: 10px 20px;
    margin: 0 10px;
    border-bottom: 3px solid #dee2e6;
    color: #6c757d;
    cursor: default;
    transition: all 0.3s ease;
}

.checkout-steps span.active {
    border-bottom-color: #007bff;
    color: #007bff;
    font-weight: 600;
}

.checkout-step {
    transition: all 0.3s ease;
}

.checkout-box {
    background: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 30px;
    transition: all 0.3s ease;
}

.section-title {
    color: #2c3e50;
    font-weight: 600;
    border-bottom: 2px solid #cb7000;
    padding-bottom: 10px;
    margin-bottom: 20px;
}

.text-primary-new{
    color:#4a4848;
}

.bg-primary-new{
background-color: #cb7000;
}
.product-row {
    padding: 15px 0;
    transition: all 0.3s ease;
}

.product-row:not(:last-child) {
    border-bottom: 1px solid #e9ecef;
}

.product-row:hover {
    background: #f8f9fa;
    border-radius: 8px;
    padding-left: 10px;
    padding-right: 10px;
}

.order-item-preview {
    padding: 12px 0;
    transition: all 0.3s ease;
}

.order-item-preview:hover {
    background: #f8f9fa;
    border-radius: 8px;
    padding-left: 10px;
    padding-right: 10px;
}

.price-breakdown .d-flex {
    padding: 5px 0;
}

.badge {
    font-size: 0.75em;
}

.form-label {
    font-weight: 500;
    color: #495057;
    margin-bottom: 8px;
}

.form-control {
    border-radius: 8px;
    padding: 10px 15px;
    border: 1px solid #ced4da;
    transition: all 0.3s;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
}

.form-control.is-invalid {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220,53,69,0.25);
}

.btn-primary {
   background-color: #cb7000;
    border: none;
    border-radius: 8px;
    padding: 12px 20px;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,123,255,0.3);
}

.btn-outline-secondary {
    border-radius: 8px;
    padding: 8px 16px;
    transition: all 0.3s;
}

.btn-outline-secondary:hover {
    transform: translateY(-1px);
}

.alert {
    border-radius: 8px;
    border: none;
}

.card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.card-header {
    background: #f8f9fa !important;
    border-bottom: 1px solid #e9ecef;
    font-weight: 600;
}

.logincheckout a {
    color: #007bff;
    text-decoration: none;
    font-weight: 500;
}

.logincheckout a:hover {
    text-decoration: underline;
}

.text-success{
    color: #009334 !important;
    font-weight: 500;
}
.card-body{
    text-align: left;
}
.quick-summary {
    border: 1px solid #e9ecef;
}

.help-section .card {
    background: linear-gradient(135deg, #e7f3ff, #d1edff);
}

/* Animation for invalid fields */
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

/* Responsive Design */
@media (max-width: 768px) {
    .checkout-box {
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .checkout-steps span {
        padding: 8px 15px;
        margin: 0 5px;
        font-size: 0.9em;
    }
    
    .section-title {
        font-size: 1.3rem;
    }
    
    .order-item-preview {
        padding: 10px 0;
    }
}

/* Smooth transitions for step changes */
.checkout-step {
    opacity: 1;
    transform: translateX(0);
    transition: all 0.3s ease;
}

/* Loading animation */
.spinner-border-sm {
    width: 1rem;
    height: 1rem;
}

.order-items-preview {
    max-height: 400px;
    overflow-y: auto;
    padding-right: 10px;
}

.order-items-preview::-webkit-scrollbar {
    width: 6px;
}

.order-items-preview::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.order-items-preview::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

.order-items-preview::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>
<style>
.checkout-steps {
    margin: 30px 0;
    padding: 0 20px;
}

.checkout-steps span {
    padding: 10px 20px;
    margin: 0 10px;
    border-bottom: 3px solid #dee2e6;
    color: #6c757d;
    cursor: default;
}

.checkout-steps span.active {
    border-bottom-color: #007bff;
    color: #007bff;
    font-weight: 600;
}

.checkout-step {
    transition: all 0.3s ease;
}

.checkout-box {
    background: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}



.form-control.is-invalid {
    border-color: #dc3545;
}

.form-control.is-valid {
    border-color: #28a745;
}

.btn-primary {
   background-color: #cb7000;
    border: none;
    border-radius: 8px;
    padding: 12px 20px;
    font-weight: 600;
}

.btn-primary:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.fw-bold{
    color: #cb7000;
}
</style>