 <footer class="hoind-footer">
     <div class="container ps-0 pe-0">
         <div class="footer-top d-flex flex-wrap justify-content-between pt-4">

             <div class="footer-column">
                 <div class="footerlogo">
                     <a href="{{ url('/') }}">@if(!empty($setting->image))
                         <img src="{{ url('userassets/image/' . $setting->image) }}"
                             alt="Ajhuie Book Store & Photostat Services logo">
                         @else
                         <img src="{{ url('userassets/image/logo.png') }}"
                             alt="Ajhuie Book Store & Photostat Services logo">
                         @endif</a>
                 </div>
                 <p class="footer-description">
                     AJHUIE Books Store & Photostate offers books, printing, and photocopy services with fast,
                     affordable, reliable support.
                 </p>

             </div>


             <div class="footer-column">
                 <h4>Quick Links :</h4>
                 <ul>
                     <li><a href="{{ url('/') }}"><span><i class="fas fa-angle-right text-white"></i></span>Home</a>
                     </li>
                     <!-- <li><a href="#"><span><i class="fas fa-angle-right text-white"></i></span>Shop</a></li> -->
                     <li><a href="{{ url('/about-us') }}"><span><i
                                     class="fas fa-angle-right text-white"></i></span>About Us</a></li>
                     <li>
                         <a href="{{ url('/contact-us') }}">
                             <span><i class="fas fa-angle-right text-white"></i></span> Contact Us
                         </a>
                     </li>

                     <li><a href="{{ url('/shipping-returns') }}"><span><i
                                     class="fas fa-angle-right text-white"></i></span>Shipping & Returns</a>
                     </li>
                     <li><a href="{{ url('/help-support') }}"><span><i
                                     class="fas fa-angle-right text-white"></i></span>Help & Support</a></li>
                 </ul>
             </div>

             <div class="footer-column">
                 <h4>Customer Service :</h4>
                 <ul>
                     @auth
                     <!--<li>-->
                     <!--    <a href="javascript:void(0)" class="open-modal" data-modal="trackOrderModal">-->
                     <!--        <span><i class="fas fa-angle-right text-white"></i></span>-->
                     <!--        Track Order-->
                     <!--    </a>-->
                     <!--</li>-->
                     @endauth
                     <li><a href="{{ url('/blog') }}"><span><i class="fas fa-angle-right text-white"></i></span>Blog</a>
                     </li>


                     <li><a href="{{ url('/return-policy') }}"><span><i
                                     class="fas fa-angle-right text-white"></i></span> Refund Policy</a></li>

                     <li><span><a href="{{ url('/privacy-policy') }}"><span><i
                                         class="fas fa-angle-right text-white"></i></span>Privacy Policy</a>
                     </li>
                     <p><i class="fa-solid fa-mobile-screen"></i>{{ $setting->phone ?? '' }}</p>
                 </ul>
             </div>


             <div class="footer-column">
                 <h4>Get in Touch :</h4>
                 <p>
                     {{ $setting->address ?? '' }}
                 </p>

                 <p style="font-size:16px"><i class="fa-solid fa-envelope "></i>{{ $setting->email ?? '' }}</p>
             </div>

             <div class="footer-column newsletter-column">
                 <h4>Newsletter :</h4>

                        <form id="newsletterForm" class="newsletter-form">
                            @csrf
                            <input type="email" name="email" id="email" placeholder="Enter your email" required />
                            @error('email')
                               <p id="message" style="display:none;"></p>
                            @enderror
                            <button type="submit">Subscribe</button>
                        </form>

                        @if(session('success'))
                            <p style="color:green">{{ session('success') }}</p>
                        @endif

                 <div class="footer-social-icons">
                     <div class="footer-social-icons">
                         @if(!empty($setting->instagram))
                         <a href="{{ $setting->instagram }}" target="_blank">
                             <img src="{{ url('userassets/image/instagram.svg') }}" alt="Instagram">
                         </a>
                         @endif

                         @if(!empty($setting->linkedin))
                         <a href="{{ $setting->linkedin }}" target="_blank">
                             <img src="{{ url('userassets/image/link.svg') }}" alt="LinkedIn">
                         </a>
                         @endif

                         @if(!empty($setting->facebook))
                         <a href="{{ $setting->facebook }}" target="_blank">
                             <img src="{{ url('userassets/image/facebook.svg') }}" alt="Facebook">
                         </a>
                         @endif

                         @if(!empty($setting->youtube))
                         <a href="{{ $setting->youtube }}" target="_blank">
                             <img src="{{ url('userassets/image/youtube.svg') }}" alt="YouTube">
                         </a>
                         @endif
                     </div>


                 </div>
             </div>

         </div>


 </footer>
 <div class="subfooter">
     <div class="container">
         <div class="row">
             <div class="footer-popular-searches">
                 <h4>Popular Searches :</h4>
                 <div class="popular-links">


                     @foreach($navcategories as $category)
                     <a data-menu="{{ Str::slug($category->title) }}" href="/{{ $category->slug }}">
                         {{ $category->title }}
                     </a>
                     @endforeach
                 </div>
             </div>

             <div class="footer-bottom d-flex justify-content-between align-items-center  pt-3 border-top">
                 <p>© 2025 Ajhuie Books store & Photostate. All Rights Reserved.</p>
                 <div class="footer-payment-icons">

                     <a href="{{ '/' }}"><img src="{{ url('userassets/image/mastercard.png')}}" alt=""></a>
                     <a href="{{ '/' }}"><img src="{{ url('userassets/image/rupay.png')}}" alt=""></a>
                     <a href="{{ '/' }}"><img src="{{ url('userassets/image/upi.png')}}" alt=""></a>
                     <a href="{{ '/' }}"><img src="{{ url('userassets/image/visa.png')}}" alt=""></a>
                 </div>
             </div>
         </div>
     </div>
 </div>
 </div>
 <div id="trackOrderModal" class="dropdown_modalTop">
     <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">

             <div class="modal-header">
                 <h5 class="modal-title">Track Your Order</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
             </div>

             <form action="{{ route('order.track.submit') }}" method="POST">
                 @csrf

                 <div class="modal-body">

                     @if(session('error'))
                     <div class="alert alert-danger">
                         {{ session('error') }}
                     </div>
                     @endif

                     <div class="mb-3">
                         <label class="form-label">Order Number</label>
                         <input type="text" name="order_number" class="form-control" required>
                     </div>

                     <!-- <div class="mb-3">
                        <label class="form-label">Email or Phone</label>
                        <input type="text" name="contact" class="form-control">
                    </div> -->

                 </div>

                 <div class="modal-footer">
                     <button type="submit" class="btn btn-primary w-100">
                         Track Order
                     </button>
                 </div>
             </form>

         </div>
     </div>
 </div>
 <!------------------------ Footer end ----------------------------->

 <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
 <script src="{{ url('userassets/js/script.js')}}"></script>



 <!-- search prodcut ki new js hai jo mobile or destop dono ke liye hai  -->
 <script>
$(document).ready(function() {

    $('#searchProduct, #searchProductMobile').on('keyup', function() {

        let query = $(this).val();
        let resultBox = $(this).attr('id') === 'searchProductMobile' ?
            '#searchResultsMobile' :
            '#searchResults';

        if (query.length > 0) {

            $.ajax({
                url: "{{ route('product.search') }}",
                type: "GET",
                data: {
                    query: query
                },

                success: function(data) {

                    let html = '';

                    if (data.length > 0) {

                        html += '<ul class="list-group">';

                        $.each(data, function(index, product) {

                            html += `
<li class="list-group-item search-item"
data-url="/product/${product.slug}">
<img src="{{ url('userassets/image/product') }}/${product.image}"
width="40" height="40"
style="object-fit:cover;margin-right:8px;">
${product.name}
</li>`;
                        });

                        html += '</ul>';

                    }

                    $(resultBox).html(html).show();

                }

            });

        } else {

            $(resultBox).hide().html('');

        }

    });

    $(document).on('click', '.search-item', function() {

        let url = $(this).data('url');
        window.location.href = url;

    });

});
 </script>

 <!-- mobile ke liye search clik par open hoga js start -->

 <script>
$(document).ready(function() {

    $('#mobileSearchBox').hide();

    $('#mobileSearchIcon').click(function() {
        $('#mobileSearchBox').slideToggle(200);
    });

});
 </script>
 <!-- mobile ke liye search clik par open hoga js end -->

 <script>
// Global function to update header cart count
function updateHeaderCartCount(newCount) {
    // ✅ id aur class dono ko target karo — desktop + mobile dono update honge
    document.querySelectorAll('#headerCartCount, .headerCartCount').forEach(el => {
        el.textContent = newCount;
        el.style.transform = 'translate(-50%, -50%) scale(1.2)';
        setTimeout(() => {
            el.style.transform = 'translate(-50%, -50%) scale(1)';
        }, 800);
    });
}

// Function to refresh cart count from server (fallback)
function refreshCartCount() {
    fetch('/cart/count')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateHeaderCartCount(data.cart_count);
            }
        })
        .catch(error => console.error('Error refreshing cart count:', error));
}

// Global function to show temporary messages
function showTempMessage(message, type) {
    // Remove any existing messages
    const existingMsg = document.querySelector('.temp-message');
    if (existingMsg) {
        existingMsg.remove();
    }

    // Create new message
    const messageDiv = document.createElement('div');
    messageDiv.className =
        `temp-message alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
    messageDiv.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    messageDiv.innerHTML = `
        <strong>${type === 'success' ? 'Success!' : 'Error!'}</strong> ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(messageDiv);

    // Auto remove after 3 seconds
    setTimeout(() => {
        if (messageDiv.parentElement) {
            messageDiv.remove();
        }
    }, 800);
}

// Initialize cart count when page loads
document.addEventListener('DOMContentLoaded', function() {
    // You can optionally refresh cart count on page load
    // refreshCartCount();
});
 </script>
 <script>
// Add to Cart AJAX functionality
document.addEventListener('DOMContentLoaded', function() {
    const addToCartForms = document.querySelectorAll('.add-to-cart-form');

    addToCartForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const submitButton = form.querySelector('.add-to-cart-btn');
            const originalText = submitButton.innerHTML;
            const productId = form.getAttribute('data-product-id');

            // Show loading state
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="bi bi-bag"></i> Adding...';

            // Get form data
            const formData = new FormData(form);

            fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // ✅ Update header cart count
                        if (data.cart_count !== undefined) {
                            if (typeof updateHeaderCartCount === 'function') {
                                updateHeaderCartCount(data.cart_count);
                            }
                        }

                        if (typeof showTempMessage === 'function') {
                            showTempMessage(data.message, 'success');
                        } else {
                            alert(data.message);
                        }
                    } else {
                        if (typeof showTempMessage === 'function') {
                            showTempMessage(data.message, 'error');
                        } else {
                            alert(data.message);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (typeof showTempMessage === 'function') {
                        showTempMessage('Error adding product to cart.', 'error');
                    } else {
                        alert('Error adding product to cart.');
                    }
                })
                .finally(() => {
                    // Reset button state
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
                });
        });
    });
});
 </script>
 <script>
// Global function to update wishlist count
function updateWishlistCount(newCount) {
    const wishlistCountElement = document.getElementById('wishlistCount');
    if (wishlistCountElement) {
        wishlistCountElement.textContent = newCount;

        // Add animation for better UX
        wishlistCountElement.style.transform = 'translate(-50%, -50%) scale(1.2)';
        setTimeout(() => {
            wishlistCountElement.style.transform = 'translate(-50%, -50%) scale(1)';
        }, 800);
    }
}

// Function to refresh wishlist count from server
function refreshWishlistCount() {
    fetch('{{ route("wishlist.count") }}')
        .then(response => response.json())
        .then(data => {
            if (data.count !== undefined) {
                updateWishlistCount(data.count);
            }
        })
        .catch(error => console.error('Error refreshing wishlist count:', error));
}

// Initialize wishlist count when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Optional: Refresh count on page load to ensure it's current
    // refreshWishlistCount();
});
 </script>
 <script>
// Global wishlist toggle function
function toggleWishlist(iconElement, productId) {
    // Show loading state
    const originalHTML = iconElement.innerHTML;
    iconElement.style.opacity = '0.6';
    iconElement.style.pointerEvents = 'none';

    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('{{ route("wishlist.toggle") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                product_id: productId
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'added') {
                // Added to wishlist
                iconElement.classList.remove('bi-heart');
                iconElement.classList.add('bi-heart-fill', 'text-danger');

                // Show success message
                if (typeof showTempMessage === 'function') {
                    showTempMessage('Product added to wishlist!', 'success');
                }
            } else if (data.status === 'removed') {
                // Removed from wishlist
                iconElement.classList.remove('bi-heart-fill', 'text-danger');
                iconElement.classList.add('bi-heart');

                // Show success message
                if (typeof showTempMessage === 'function') {
                    showTempMessage('Product removed from wishlist!', 'success');
                }
            }

            // ✅ Update header wishlist count
            if (data.count !== undefined && typeof updateWishlistCount === 'function') {
                updateWishlistCount(data.count);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof showTempMessage === 'function') {
                showTempMessage('Error updating wishlist. Please try again.', 'error');
            }
        })
        .finally(() => {
            // Reset button state
            iconElement.style.opacity = '1';
            iconElement.style.pointerEvents = 'auto';
        });
}

// Initialize wishlist icons when page loads
document.addEventListener('DOMContentLoaded', function() {
    // You can add any initialization code here if needed
});
 </script>
 <script>
$(document).ready(function(){

    $('#newsletterForm').submit(function(e){
        e.preventDefault();

        let email = $('#email').val();
        let token = $('input[name="_token"]').val();

        $.ajax({
            url: "{{ route('newsletter.subscribe') }}",
            type: "POST",
            data: {
                _token: token,
                email: email
            },
            success: function(response){
                $('#message')
                    .text(response.message)
                    .css('color', 'green')
                    .fadeIn();

                $('#newsletterForm')[0].reset();

                // 2 sec baad message hide
                setTimeout(function(){
                    $('#message').fadeOut();
                }, 2000);
            },
            error: function(xhr){
                let error = xhr.responseJSON.errors.email[0];

                $('#message')
                    .text(error)
                    .css('color', 'red')
                    .fadeIn();

                setTimeout(function(){
                    $('#message').fadeOut();
                }, 2000);
            }
        });

    });

});
</script>
 </body>

 </html>