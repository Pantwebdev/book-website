@include('userheader')

<div class="product_listbanner">
    <img src="{{url('userassets/image/banner-list.png')}}" alt="">
</div>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-3 shadow-sm" role="alert"
                style="border-left: 4px solid #28a745; border-radius: 0.375rem;">
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill me-2" style="font-size: 1.2rem;"></i>
                    <strong class="me-2">Success!</strong> {{ session('success') }}
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mt-3 shadow-sm" role="alert"
                style="border-left: 4px solid #dc3545; border-radius: 0.375rem;">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill me-2" style="font-size: 1.2rem;"></i>
                    <strong class="me-2">Error!</strong> {{ session('error') }}
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
<section>

    <div class="cart-container container-fluid">
        <div class="cart-left">
            @foreach($cartItems as $item)
            <div class="section-box product-item" data-item-id="{{ $item->id }}">
                <div class="product-box">
                    <img src="{{ url('userassets/image/product/' . ($item->image ?? 'placeholder.png')) }}"
                        alt="Product" />
                    <div class="product-info">
                        <h6 >{{ $item->product->name ?? $item->name }}</h6>
                        <a href="" class="addtocartsku">ISBN:
                            {{ $item->product->sku ?? 'Product SKU' }}</a>

                        
                        <div class="actions qty-container buttonincrege mb-2">
                            <button class="qty-btn" onclick="updateCartQuantity({{ $item->id }}, -1)">-</button>
                            <input type="text" value="{{ $item->qty }}" id="qtyInput{{ $item->id }}" class="qty-input"
                                readonly>
                            <button class="qty-btn" onclick="updateCartQuantity({{ $item->id }}, 1)">+</button>
                        </div>

                        <div class="mt-1">
                            <span class="price" id="itemTotal{{ $item->id }}" data-price="{{ $item->price }}">
                                ₹{{ number_format($item->price * $item->qty, 2) }}
                            </span>
                            <span class="old-price ms-2" id="itemMrp{{ $item->id }}" data-mrp="{{ $item->mrp_price }}">
                                ₹{{ number_format($item->mrp_price * $item->qty, 2) }}
                            </span>
                            <span class="discount ms-2">
                                {{ $item->discount }}% OFF
                            </span>
                        </div>

                        <div class="action-btns">
                            <button type="button" class="remove-btn" onclick="removeCartItem({{ $item->id }})">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                            <button class="wishlist-btn"><i class="bi bi-heart"></i></button>
                              <span class="badge bg-light text-dark small">
                                    <i class="bi bi-box me-1"></i>Qty: {{ $item->qty }}
                              </span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Right Side -->
        <div class="cart-right">
            <div class="price-box">
                <h6>COUPONS</h6>
                <div id="couponSection">
                    <input type="text" id="couponInput" placeholder="Enter coupon code" />
                    <button id="applyCouponBtn">APPLY</button>
                    <div id="couponMessage" style="margin-top: 5px; color: green;"></div>
                    <button id="editCouponBtn" style="display:none; margin-top: 5px;">EDIT</button>
                </div>

                <hr />


                <h6 id="detailsprice">Price Details (<span id="itemCount">{{ $itemCount }}</span>
                    Item{{ $itemCount > 1 ? 's' : '' }})</h6>

                <div class="price-row">
                    <span>Total MRP :</span>
                    <span id="totalMRP">₹ {{ number_format($totalMRP, 2) }}</span>
                </div>

                <div class="price-row">
                    <span>Discount on MRP :</span>
                    <span class="text-success" id="totalDiscount">-₹ {{ number_format($totalDiscountAmount, 2) }}</span>
                </div>

                <div class="price-row">
                    <span>Coupon Discount :</span>
                    <span id="couponDiscount">-₹ {{ number_format($couponDiscount, 2) }}</span>
                </div>

               
                <div class="price-row">
    <span>Shipping :</span>
    <span id="shippingCharge">
        @if($totalShipping == 0)
            <span class="text-success">FREE</span>
        @else
            ₹ {{ number_format($totalShipping, 2) }}
        @endif
    </span>
</div>

                <div class="price-row total">
                    <span>Total Amount : </span>
                    <span id="totalAmount">₹ {{ number_format($grandTotal, 2) }}</span>
                </div>

                <button class="place-order" id="placeOrderBtn">PLACE ORDER</button>
            </div>
        </div>
    </div>
</section>

@include('userfooter')

{{-- Include the reusable authentication modal --}}
@include('partials.auth-modal')

<style>
    .form-label{
        display: flex;
        justify-content: left;
    }

    #customerLoginFormCart button{
        background-color: #cb7000;
    }

    #customerLoginFormCart button:hover{
        color:#c9c6c6;
    }

    #signupStep1Cart button{
         background-color: #cb7000;
    }


     #signupStep1Cart  button:hover{
        color:#c9c6c6;
     }

     #signup-tab-cart {
    color:#5f656b
     }

     #login-tab-cart{
        color:#5f656b
     }
     .modal-body{
        padding:0px 16px 16px 16px 
     }
</style>

<script>
// Update Cart Quantity Function
function updateCartQuantity(itemId, change) {
    console.log('Updating cart:', {
        itemId,
        change
    });

    const qtyInput = document.getElementById(`qtyInput${itemId}`);
    let currentQty = parseInt(qtyInput.value);
    let newQty = currentQty + change;

    // Validate minimum quantity
    if (newQty < 1) {
        console.log('Quantity cannot be less than 1');
        return;
    }

    // Update UI immediately for better UX
    qtyInput.value = newQty;

    // Show loading state
    const buttons = qtyInput.parentElement.querySelectorAll('.qty-btn');
    buttons.forEach(btn => {
        btn.style.opacity = '0.6';
        btn.style.pointerEvents = 'none';
    });

    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('CSRF token not found!');
        showTempMessage('Security token missing. Please refresh the page.', 'error');
        resetButtons(buttons);
        qtyInput.value = currentQty;
        return;
    }

    // Make AJAX call with PATCH method
    fetch(`/cart/update/${itemId}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                qty: newQty
            })
        })
        //new code
        .then(async response => {
    const data = await response.json();
    if (!response.ok) {
        // If response not OK, throw an error with the server message
        throw new Error(data.message || `HTTP error! status: ${response.status}`);
    }
    return data;
})
//new code
        // .then(response => {
        //     console.log('Response status:', response.status);
        //     if (!response.ok) {
        //         throw new Error(`HTTP error! status: ${response.status}`);
        //     }
        //     return response.json();
        // })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                // ✅ Update header cart count
                if (data.cart_count !== undefined && typeof updateHeaderCartCount === 'function') {
                    updateHeaderCartCount(data.cart_count);
                }

                // Success - Update all calculated values from server
                updateCartUI(data, itemId, newQty);
                showTempMessage('Quantity updated successfully!', 'success');
            } else {
                throw new Error(data.message || 'Failed to update cart');
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            // Revert to previous quantity on error
            qtyInput.value = currentQty;

            // Show appropriate error message
            if (error.name === 'TypeError' && error.message.includes('fetch')) {
                showTempMessage('Network error. Please check your internet connection.', 'error');
            } else {
                showTempMessage(error.message || 'Failed to update cart. Please try again.', 'error');
            }
        })
        .finally(() => {
            // Re-enable buttons
            resetButtons(buttons);
        });
}

// Remove Cart Item Function
function removeCartItem(itemId) {
    if (confirm("Are you sure you want to remove this item from cart?")) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const url = window.location.origin + `/cart/remove/${itemId}`;
        console.log('DELETE URL:', url);

        fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({})
            })
            .then(response => {
                console.log('Response Status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response Data:', data);
                if (data.success) {
                    if (data.cart_count !== undefined && typeof updateHeaderCartCount === 'function') {
                        updateHeaderCartCount(data.cart_count);
                    }

                    const itemElement = document.querySelector(`[data-item-id="${itemId}"]`);
                    if (itemElement) {
                        itemElement.remove();
                    }

                    showTempMessage(data.message, 'success');

                    setTimeout(() => {
                        location.reload();
                    }, 800);
                } else {
                    showTempMessage(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showTempMessage('Error removing item from cart.', 'error');
            });
    }
}

// Helper function to reset buttons
function resetButtons(buttons) {
    buttons.forEach(btn => {
        btn.style.opacity = '1';
        btn.style.pointerEvents = 'auto';
    });
}

// Helper function to update UI after successful update
function updateCartUI(data, itemId, newQty) {
    const itemTotalEl = document.getElementById(`itemTotal${itemId}`);
    const itemMrpEl = document.getElementById(`itemMrp${itemId}`);

    if (itemTotalEl && data.item_total !== undefined) {
        itemTotalEl.textContent = '₹' + parseFloat(data.item_total).toFixed(2);
    }

    if (itemMrpEl && data.item_mrp !== undefined) {
        itemMrpEl.textContent = '₹' + parseFloat(data.item_mrp).toFixed(2);
    }

    updateCartSummary(data);
}

// Function to update entire cart summary including taxes
function updateCartSummary(data) {
    const totalAmountEl = document.getElementById('totalAmount');
    const totalMRPEl = document.getElementById('totalMRP');
    const totalDiscountEl = document.getElementById('totalDiscount');
    const itemCountEl = document.getElementById('itemCount');
    const detailsPriceEl = document.querySelector('#detailsprice');
    const couponDiscountEl = document.getElementById('couponDiscount');
   

    if (totalAmountEl && data.grand_total_with_tax !== undefined) {
        totalAmountEl.textContent = '₹' + parseFloat(data.grand_total_with_tax).toFixed(2);
    }

    if (totalMRPEl && data.total_mrp !== undefined) {
        totalMRPEl.textContent = '₹' + parseFloat(data.total_mrp).toFixed(2);
    }

    if (totalDiscountEl && data.total_discount !== undefined) {
        totalDiscountEl.textContent = '-₹' + parseFloat(data.total_discount).toFixed(2);
    }

    if (itemCountEl && data.item_count !== undefined) {
        itemCountEl.textContent = data.item_count;
    }

    if (couponDiscountEl && data.coupon_discount !== undefined) {
        couponDiscountEl.textContent = '-₹' + parseFloat(data.coupon_discount).toFixed(2);
    }

    const shippingEl = document.getElementById('shippingCharge');
if (shippingEl && data.shipping_charge !== undefined) {
    if (parseFloat(data.shipping_charge) === 0) {
        shippingEl.innerHTML = '<span class="text-success">FREE</span>';
    } else {
        shippingEl.textContent = '₹' + parseFloat(data.shipping_charge).toFixed(2);
    }
}

    if (detailsPriceEl && data.item_count !== undefined) {
        const itemCountText = data.item_count + ' Item' + (data.item_count > 1 ? 's' : '');
        detailsPriceEl.innerHTML = `PRICE DETAILS (${itemCountText})`;
    }
}

// Auto-hide success/error messages
setTimeout(() => {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        alert.classList.remove('show');
        setTimeout(() => alert.remove(), 300);
    });
}, 800);

// Coupon Functionality
const couponInput = document.getElementById("couponInput");
const applyCouponBtn = document.getElementById("applyCouponBtn");
const couponMessage = document.getElementById("couponMessage");
const editCouponBtn = document.getElementById("editCouponBtn");

applyCouponBtn.addEventListener("click", () => {
    const code = couponInput.value.trim();
    if (!code) {
        couponMessage.style.color = "red";
        couponMessage.textContent = "Please enter a coupon code!";
        return;
    }

    applyCouponBtn.disabled = true;
    applyCouponBtn.innerHTML =
        '<span class="spinner-border spinner-border-sm" role="status"></span> Applying...';

    fetch(`/apply-coupon`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                "Accept": "application/json"
            },
            body: JSON.stringify({
                code
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                couponMessage.style.color = "green";
                couponMessage.textContent = data.message;

                updateCartSummary(data);

                couponInput.disabled = true;
                applyCouponBtn.style.display = "none";
                editCouponBtn.style.display = "inline-block";

                showTempMessage('Coupon applied successfully!', 'success');
            } else {
                couponMessage.style.color = "red";
                couponMessage.textContent = data.message;
                showTempMessage(data.message, 'error');
            }
        })
        .catch(err => {
            console.error(err);
            couponMessage.style.color = "red";
            couponMessage.textContent = "Error applying coupon!";
            showTempMessage('Error applying coupon!', 'error');
        })
        .finally(() => {
            applyCouponBtn.disabled = false;
            applyCouponBtn.innerHTML = 'APPLY';
        });
});

editCouponBtn.addEventListener("click", () => {
    fetch('/remove-coupon', {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
            }
        })
        .then(() => {
            location.reload();
        })
        .catch(err => {
            console.error(err);
            location.reload();
        });
});

if (couponInput) {
    couponInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            applyCouponBtn.click();
        }
    });
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check if we need to open login modal after password reset
    const openLoginModal = {{ session('open_login_modal') ? 'true' : 'false' }};
    
    if (openLoginModal) {
        // Open the cart page's auth modal after short delay
        setTimeout(() => {
            if (typeof openAuthModal === 'function') {
                openAuthModal();
                
                // Show success message in login modal
                const loginMessage = document.getElementById('loginMessageCart');
                if (loginMessage) {
                    loginMessage.innerHTML = `
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            Password reset successful! Please login with your new password.
                        </div>
                    `;
                    loginMessage.style.display = 'block';
                }
                
                // Clear the session flag (optional)
                fetch('/clear-login-modal-flag', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
            }
        }, 500);
    }
    
    // Place Order button click event
    const placeOrderBtn = document.getElementById("placeOrderBtn");
    
    if (placeOrderBtn) {
        placeOrderBtn.addEventListener("click", function(e) {
            e.preventDefault();
            const isAuthenticated = {{ auth()->guard('customer')->check() ? 'true' : 'false' }};
            if (isAuthenticated) {
                window.location.href = '/customer/checkout';
            } else {
                if (typeof openAuthModal === 'function') {
                    openAuthModal();
                } else {
                    console.error('openAuthModal function not available');
                }
            }
        });
    }
});
</script>