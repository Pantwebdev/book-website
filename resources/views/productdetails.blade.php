@include('userheader')
<section class="">
   <div class="pproduct-details">
        <div class="left">
            <div class="thumbs-wrapper">
                <button class="scroll-btn thumb-up"><i class="fa-solid fa-chevron-up"></i></button>
                <div class="thumbs" id="thumbs">
                    <img src="{{ url('userassets/image/product/' . $product->image) }}"
                        data-zoom="{{ url('userassets/image/product/' . $product->image) }}" alt="{{ $product->name }}"
                        class="active">
                    @php
                    $images = collect([
                    $product->image2,
                    $product->image3,
                    $product->image4,
                    $product->image5,
                    ])->filter();

                    if (!empty($product->multipleimage) && is_array($product->multipleimage)) {
                    $images = $images->merge($product->multipleimage);
                    }
                    @endphp

                    @foreach ($images as $index => $img)
                    <img src="{{ url('userassets/image/product/' . $img) }}"
                        data-zoom="{{ url('userassets/image/product/' . $img) }}"
                        class="{{ $index === 0 ? 'active' : '' }}">
                    @endforeach
                </div>
                <button class="scroll-btn thumb-down"><i class="fa-solid fa-chevron-down"></i></button>
            </div>
            <div class="stage main-mobile" id="stage">
                <img id="mainImg" src="{{ url('userassets/image/product/' . $product->image) }}"
                    data-zoom="{{ url('userassets/image/product/' . $product->image) }}" alt="{{ $product->name }}"
                    class="active">
            </div>
            <div class="zoom-result" id="zoomResult"></div>
        </div>
        <div class="right-wrapper">
            <div class="right">
                <h1 class="title product-dotss">{{ $product->name }}</h1>

                <div class="rating">
                    @php
                    $fullStars = floor($avgRating);
                    $hasHalfStar = ($avgRating - $fullStars) >= 0.5;
                    $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
                    $stars = '';
                    for ($i = 0; $i < $fullStars; $i++) { $stars .='★' ; } if ($hasHalfStar) { $stars .='½' ; } for
                        ($i=0; $i < $emptyStars; $i++) { $stars .='☆' ; } @endphp @if($totalReviews> 0)
                        <span class="stars-display">{{ $stars }}</span>
                        <span class="rating-number">({{ number_format($avgRating, 1) }})</span>
                        <span class="rating-count">Based on {{ $totalReviews }}
                            review{{ $totalReviews > 1 ? 's' : '' }}</span>
                        @endif
                </div>
                <div class="price-row-productdetails">

                    @if(!empty($product->display_price))
                    {{-- Discounted Price --}}
                    <span class="price" id="displayPrice">
                        ₹{{ $product->display_price }}
                    </span>

                    <input type="hidden" id="basePrice" value="{{ $product->display_price }}">

                    <span class="old-price" style="text-decoration: line-through; color:#888; margin-left:8px;">
                        ₹{{ $product->mrp_price }}
                    </span>

                    @if($product->discount > 0)
                    <span class="discount">{{ $product->discount }}% off</span>
                    @endif

                    @else
                    {{-- Only MRP --}}
                    <span class="price" id="displayPrice">
                        ₹{{ $product->mrp_price }}
                    </span>

                    <input type="hidden" id="basePrice" value="{{ $product->mrp_price }}">

                    @endif

                </div>

                <div class="discripmennul">
                    <span id="descText">{!! $product->description !!}</span>
                    <a href="javascript:void(0)" id="seeMoreBtn">See More</a>
                </div>


            </div>
            <div class="productInformation">
                <h5>Information</h5>

                <table class="infoTable">
                    <tr>
                        <td class="label">Name</td>
                        <td>{{ $product->name }}</td>
                    </tr>

                    <tr>
                        <td class="label">ISBN</td>
                        <td>{{ $product->sku }}</td>
                    </tr>

                    <tr>
                        <td class="label">Author</td>

                        <td>{{ $product->author }}</td>

                    </tr>

                    <tr>
                        <td class="label">Edition</td>
                        <td>{{ $product->edition }}</td>
                    </tr>

                    <tr>
                        <td class="label">Publisher</td>
                        <td>{{ $product->publisher }}</td>
                    </tr>

                    <tr>
                        <td class="label">Publish Year</td>
                        <td>{{ $product->published_date }}</td>
                    </tr>
                </table>
            </div>

            <div class="product-cart-box">

                <!-- Quantity -->
                 <div class="qty-container">
                    <button type="button" class="qty-btn" onclick="changeQty(-1)">-</button>
                    <input type="text" value="1" id="qtyInput" class="qty-input" readonly>
                    <button type="button" class="qty-btn" onclick="changeQty(1)">+</button>
                </div>
                <p id="stockMessage" style="color:red;"></p>

                @if($product->stock > 0)
                <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form "
                    data-product-id="{{ $product->id }}">
                    @csrf

                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="name" value="{{ $product->name }}">
                    <input type="hidden" name="price" id="formPrice" value="{{ $product->display_price }}">
                    <input type="hidden" name="mrp_price" id="formMrpPrice" value="{{ $product->mrp_price }}">
                    <input type="hidden" name="discount" id="formDiscount" value="{{ $product->discount }}">
                    <input type="hidden" name="qty" id="formQty" value="1">

                    <input type="hidden" name="image" value="{{ $product->image }}">

                    <div class="cart-action-wrapper">

                        <button type="submit" class="add-to-cart-btn">
                            <i class="bi bi-bag"></i> Add to Cart
                        </button>
                        
                        
                        

                    </div>

                </form>
                @else

                <button class="add-to-cart-btn out-stock" disabled>
                    <i class="bi bi-bag-x"></i> Out of Stock
                </button>

                @endif


                    <!--<div class="View-simple"> -->
                    <!--      <button type="" class="add-to-cart-btn ">-->
                    <!--         View sample-->
                    <!--    </button>-->
                    <!--</div>-->
                    
                    @if($product->product_pdf)
                    <div class="View-simple">
                   
                        <a href="{{ asset('userassets/image/product/pdf/' . $product->product_pdf) }}" 
                        target="_blank" 
                        class="add-to-cart-btn">
                            View Sample
                        </a>
                    </div>
                     @endif
                     
                     
                    <div>
                          <a href="#" onclick="shareOnWhatsApp()" class="whatsapp-share">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
            </div>

        </div>

    </div>

    <!-- Popup Structure -->
    <div id="popup">
        <div id="popupContent">
            <div id="popupMainWrapper">
                <img id="popupMain" src="" alt="">
            </div>
            <button id="popupClose" class="popup-close">×</button>
        </div>
    </div>
</section>
<!-- product details page end  -->


<!-- review start  -->
<section class="pradeep121">
    <div class="container">

        <div class="heading-sectionnew">
            <div class="title">Customer Reviews</div>

            <div class="dividerswction">
                <img src="{{url('userassets/image/flowericon-img.png')}}" alt="decorative icon">
            </div>
        </div>

        <div class="review-summary row align-items-center text-center text-md-start">
            <div class="col-md-3 mb-3 mb-md-0">

                @php
                $rounded = floor($avgRating);
                @endphp

                <div class="stars">
                    {!! str_repeat('★', $rounded) !!}
                    {!! str_repeat('☆', 5 - $rounded) !!}
                </div>

                <div class="mt-2">
                    <strong>{{ number_format($avgRating, 2) }} out of 5</strong><br>
                    Based on {{ $totalReviews }} reviews ✅
                </div>
            </div>


            <div class="col-md-6">
                @foreach ([5,4,3,2,1] as $star)
                @php
                $count = $ratingCount[$star];
                $percentage = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                @endphp

                <div class="rating-bar">
                    <span>
                        {!! str_repeat('★', $star) !!}{!! str_repeat('☆', 5 - $star) !!}
                    </span>

                    <div class="bar">
                        <div class="bar-fill" style="width: {{ $percentage }}%"></div>
                    </div>

                    <span>{{ $count }}</span>
                </div>
                @endforeach
            </div>

            <div class="col-md-3 text-md-end">
                <button class="btn-open-review" id="openReviewBtn">Write a review</button>
            </div>
        </div>

        <div class="sort-section">
            <div class="fw-bold ">Sort by:</div>
            <select id="sortSelect" class="sort-select">
                <option value="recent">Most Recent</option>
                <option value="high">Highest Rating</option>
                <option value="low">Lowest Rating</option>
            </select>
        </div>

        <div id="reviewsList" class="reviewmannulcss"></div>


        <div class="row">
            @foreach($relatedreview as $rr)
            <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                <div class="reviewmaincol">

                    <div class="review">
                        <div class="mb-2 ">
                            <div class="fw-bold ms-0  product-dotss">{{ $rr->name }}</div>
                            <span class="verified">Verified</span>
                            <div class="date">{{ $rr->created_at->format('m/d/Y') }}</div>

                            <div class="stars">
                                {!! str_repeat('★', $rr->rating) !!}
                                {!! str_repeat('☆', 5 - $rr->rating) !!}
                            </div>
                        </div>

                        {{-- IMAGE SAFE CHECK --}}
                        @if($rr->image)
                        <img class="revieimg" src="{{ asset('userassets/image/reviews/'.$rr->image) }}"
                            style="max-width:100px;height:100px;cursor:pointer;object-position: top"
                            data-bs-toggle="modal" data-bs-target="#imageModal"
                            onclick="document.getElementById('fullImage').src=this.src;">
                        @endif
                    </div>

                    <div class="summaryreview">
                        <p>{{ $rr->review }}</p>
                    </div>

                </div>
            </div>


            @endforeach

            <a href="{{ route('product.reviews', $product->slug) }}" class="all-reviews-btn">
                All Reviews
            </a>
        </div>


        <div class="modal fade" id="imageModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content p-2" style="background: #fff; border: none;">

                    <!-- Close Button (floating on image) -->
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"
                        style="z-index: 10; filter: invert(1); box-shadow:unset"></button>

                    <div class="modal-body p-0 text-center">
                        <img id="fullImage" src="" class="img-fluid"
                            style="max-height: 100vh; object-fit: cover; width: 100%;object-position: top;">
                    </div>

                </div>
            </div>
        </div>



    </div>


    <div class="review-overlay" id="reviewOverlay">
        <div class="review-popup">
            <button class="btn-close-popup" id="closePopup">&times;</button>

            <h3 class="review-title">Write a Review</h3>

            <form id="reviewFormCustom" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <div class="form-group">
                    <label>Your Name</label>
                    <input type="text" name="name"
                        value="{{ auth()->guard('customer')->check() ? auth()->guard('customer')->user()->name : '' }}"
                        required>
                </div>

                <div class="form-group">
                    <label>Rating</label>
                    <div id="ratingStars" class="rating-stars">
                        <span class="star" data-value="1">★</span>
                        <span class="star" data-value="2">★</span>
                        <span class="star" data-value="3">★</span>
                        <span class="star" data-value="4">★</span>
                        <span class="star" data-value="5">★</span>
                    </div>
                    <input type="hidden" name="rating" id="rating" required>
                </div>

                <div class="form-group">
                    <label>Upload Image</label>
                    <input type="file" id="reviewImages" name="image" accept="image/*">
                </div>

                <div class="form-group">
                    <label>Your Review</label>
                    <textarea name="review" rows="1" required></textarea>
                </div>

                <button type="submit" class="btn-submit-review">Submit Review</button>

                <div id="formErrors"></div>
                <div id="formSuccess"></div>
            </form>
        </div>
    </div>

</section>

<!-- review end  -->

<!-- slider start-->
<section class="arival-main">

    <div class="heading-sectionnew">
        <div class="title">Similar Products</div>

        <div class="dividerswction">
            <img src="{{url('userassets/image/flowericon-img.png')}}" alt="decorative icon">
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                @php
                $guestToken = Cookie::get('guest_token');
                $wishlistProducts = \App\Models\Wishlist::where('guest_token', $guestToken)
                ->pluck('product_id')->toArray();
                @endphp

                @if($relatedProducts->count() > 0)
                <div class="owl-carousel arrivals-slider">
                    @foreach($relatedProducts as $related)
                    <div class="item">
                        <div class="product-card">
                            <!-- Front Image -->
                             <a href="{{ url('product/' . $related->slug) }}" class="product-image {{ empty($related->image2) ? 'single-image' : 'double-image' }}">
                                
                                 <img src="{{ url('userassets/image/product/' . $related->image) }}" alt="{{ $related->name }}" class="front">
                       @if(!empty($related->image2))
                                 <img src="{{ url('userassets/image/product/' . $related->image2) }}" alt="{{ $related->name }}" class="back">
                       @endif

                                <!-- Add button -->

                                @if($related->stock > 0)
                                <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form"
                                    data-product-id="{{ $related->id }}">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $related->id }}">
                                    <input type="hidden" name="name" value="{{ $related->name }}">
                                    <input type="hidden" name="price" id="formPrice"
                                        value="{{ $related->display_price }}">
                                    <input type="hidden" name="mrp_price" id="formMrpPrice"
                                        value="{{ $related->mrp_price }}">
                                    <input type="hidden" name="discount" id="formDiscount"
                                        value="{{ $related->discount }}">
                                    <input type="hidden" name="qty" id="formQty" value="1">
                                    <input type="hidden" name="color" id="formColor"
                                        value="{{ $colors->first() ? $colors->first()->name : '' }}">

                                    <input type="hidden" name="image" value="{{ $related->image }}">

                                    <div class="actions">
                                        <button type="submit" class="add-to-cart add-to-cart-btn">
                                            <i class="bi bi-bag"></i>
                                            <span>Add to cart</span>
                                        </button>
                                    </div>
                                </form>
                                @else
                                <button class="add-to-cart add-to-cart-btn headrtopaddtocart" disabled>
                                    <i class="bi bi-bag-x"></i>
                                    <span>Out of Stock</span>
                                </button>
                                @endif
                            </a>
                            <!-- Discount badge -->
                            @if($related->mrp_price > $related->display_price)
                            <span class="discount-badge">
                                {{ round((($related->mrp_price - $related->display_price) / $related->mrp_price) * 100) }}%

                            </span>
                            @endif

                            <!-- Heart icon -->
                            <i class="bi heart-icon {{ in_array($related->id, $wishlistProducts) ? 'bi-heart-fill text-danger' : 'bi-heart' }}"
                                data-product-id="{{ $related->id }}"
                                onclick="toggleWishlist(this, {{ $related->id }})"></i>



                            <!-- Product name & price -->
                            <p><a href="{{ url('product/' . $related->slug) }}"
                                    class="product-dotss">{{ $related->name }}</a></p>
                            <div class="skucombind">
                                <p><a href="{{ url('product/' . $related->slug) }}">ISBN:{{$related->sku}}</a></p>

                                @if($related->total_reviews > 0)
                                <div class="product-rating">
                                    @php
                                    $avgRating = $related->avg_rating;
                                    $fullStars = floor($avgRating);
                                    $hasHalfStar = ($avgRating - $fullStars) >= 0.5;
                                    $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
                                    @endphp

                                    <div class="stars">
                                        @for($i = 0; $i < $fullStars; $i++) <i class="bi bi-star-fill text-warning"></i>
                                            @endfor

                                            @if($hasHalfStar)
                                            <i class="bi bi-star-half text-warning"></i>
                                            @endif

                                            @for($i = 0; $i < $emptyStars; $i++) <i class="bi bi-star text-warning"></i>
                                                @endfor
                                                <span class="rating-number">{{ number_format($avgRating, 1) }}</span>
                                                <span class="review-count">({{ $related->total_reviews }})</span>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="product-price">
                                @if(!empty($related->display_price))
                                {{-- Discounted price --}}
                                <p>
                                    <span><i class="bi bi-currency-rupee"></i></span>{{ $related->display_price }}
                                    <span style="text-decoration: line-through; color: #888; margin-left: 5px;">
                                        <i class="bi bi-currency-rupee"></i>{{ $related->mrp_price }}
                                    </span>
                                </p>
                                @else
                                {{-- Only MRP --}}
                                <p>
                                    <span><i class="bi bi-currency-rupee"></i></span>{{ $related->mrp_price }}
                                </p>
                                @endif


                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-center">No related products found.</p>
                @endif
            </div>
        </div>
    </div>
</section>



<section class="arival-main">

    <div class="heading-sectionnew">
        <div class="title">Recent Products View</div>
        <div class="dividerswction">
            <img src="{{url('userassets/image/flowericon-img.png')}}" alt="decorative icon">
        </div>
    </div>

    <div class="container-fluid">
        <div class="row recent-products-row">

            @if($recentProducts->count() > 0)

            @foreach($recentProducts as $related)
            <div class="col-custom-5-recent ">

                <div class="product-card mobilerecent">
                    <!--<a href="{{ url('product/' . $related->slug) }}" class="product-image double-image product-spcl">-->
                    <!--    <img src="{{ url('userassets/image/product/' . $related->image) }}" class="front">-->

                    <!--    @if($related->image2)-->
                    <!--    <img src="{{ url('userassets/image/product/' . $related->image2) }}" class="back">-->
                    <!--    @endif-->
                    
                    
                    <a href="{{ url('product/' . $related->slug) }}" class="product-image product-spcl {{ empty($related->image2) ? 'single-image' : 'double-image' }}">

                        <img src="{{ url('userassets/image/product/' . $related->image) }}" alt="{{ $related->name }}" class="front">

                       @if(!empty($related->image2))
                        <img src="{{ url('userassets/image/product/' . $related->image2) }}" alt="{{ $related->name }}" class="back">
                        @endif
                        
                        <!-- Add to Cart -->
                        @if($related->stock > 0)
                        <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form"
                            data-product-id="{{ $related->id }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $related->id }}">
                            <input type="hidden" name="name" value="{{ $related->name }}">
                            <input type="hidden" name="price" value="{{ $related->display_price }}">
                            <input type="hidden" name="mrp_price" value="{{ $related->mrp_price }}">
                            <input type="hidden" name="discount" value="{{ $related->discount }}">
                            <input type="hidden" name="qty" value="1">
                            <input type="hidden" name="color"
                                value="{{ $colors->first() ? $colors->first()->name : '' }}">

                            <input type="hidden" name="image" value="{{ $related->image }}">

                            <div class="actions">
                                <button type="submit" class="add-to-cart add-to-cart-btn">
                                    <i class="bi bi-bag"></i>
                                    <span>Add to cart</span>
                                </button>
                            </div>
                        </form>
                        @else
                        <button class="add-to-cart add-to-cart-btn headrtopaddtocart" disabled>
                            <i class="bi bi-bag-x"></i>
                            <span>Out of Stock</span>
                        </button>
                        @endif
                    </a>


                    @if($related->mrp_price > $related->display_price)
                    <span class="discount-badge">
                        {{ round((($related->mrp_price - $related->display_price) / $related->mrp_price) * 100) }}%
                    </span>
                    @endif

                    <!-- Wishlist -->
                    <i class="bi heart-icon {{ in_array($related->id, $wishlistProducts) ? 'bi-heart-fill text-danger' : 'bi-heart' }}"
                        data-product-id="{{ $related->id }}" onclick="toggleWishlist(this, {{ $related->id }})"></i>



                    <!-- Name -->
                    <p><a href="{{ url('product/' . $related->slug) }}" class="product-dotss">{{ $related->name }}</a>
                    </p>
                    <div class="skucombind">
                        <!-- SKU -->
                        <p><a href="{{ url('product/' . $related->slug) }}">ISBN: {{ $related->sku }}</a></p>


                        @if($related->total_reviews > 0)
                        <div class="product-rating">
                            @php
                            $avgRating = $related->avg_rating;
                            $fullStars = floor($avgRating);
                            $hasHalfStar = ($avgRating - $fullStars) >= 0.5;
                            $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
                            @endphp

                            <div class="stars">
                                @for($i = 0; $i < $fullStars; $i++) <i class="bi bi-star-fill text-warning"></i>
                                    @endfor

                                    @if($hasHalfStar)
                                    <i class="bi bi-star-half text-warning"></i>
                                    @endif

                                    @for($i = 0; $i < $emptyStars; $i++) <i class="bi bi-star text-warning"></i>
                                        @endfor
                                        <span class="rating-number">{{ number_format($avgRating, 1) }}</span>
                                        <span class="review-count">({{ $related->total_reviews }})</span>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="product-price">
                        @if(!empty($related->display_price))
                        {{-- Discounted price --}}
                        <p>
                            <span><i class="bi bi-currency-rupee"></i></span>{{ $related->display_price }}
                            <span style="text-decoration: line-through; color: #888; margin-left: 5px;">
                                <i class="bi bi-currency-rupee"></i>{{ $related->mrp_price }}
                            </span>
                        </p>
                        @else
                        {{-- Only MRP --}}
                        <p>
                            <span><i class="bi bi-currency-rupee"></i></span>{{ $related->mrp_price }}
                        </p>
                        @endif


                    </div>
                </div>

            </div>
            @endforeach

            @else
            <p class="text-center">No related products found.</p>
            @endif

        </div>
    </div>

</section>
@include('partials.auth-modal')
<!-- Reclent view product End -->

<!-- Reclent view product End -->


<style>
/* Quantity */

.qty-container {
    display: flex;
    align-items: center;
    gap: 5px;
    /*margin-bottom: 15px;*/
}

.qty-btn {
    width: 35px;
    height: 40px;
    border: 1px solid #ccc;
    background: #f5f5f5;
    cursor: pointer;
    font-size: 18px;
}

.qty-input {
    width: 45px;
    height: 40px;
    text-align: center;
    border: 1px solid #ccc;
}

/* Cart Buttons */


/* Add to Cart */



/* WhatsApp */

.whatsapp-share {
    width: 40px;
    height: 40px;
    background: #25D366;
    color: #fff;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;

}

.whatsapp-share i {
    font-size: 26px
}

.product-cart-box {
    display: flex;
    align-items: center;
    gap: 5px;
}

.View-simple{
    width: 30%;
}

.View-simple a {
    font-size: 15px;
    font-weight: 600;
   text-decoration: none;
   display: block;
   text-align: center;
}

.View-simple a:hover{
    color: #fff;
}

.add-to-cart-form {
    width: 50%;
}

@media(max-width:500px){
    .qty-btn{
    width: 30px;
    height: 30px;
    }
    
    .qty-input{
    width: 30px;
    height: 30px;
    }
    
    .whatsapp-share{
    width: 30px;
    height: 30px;
    }
    
      .whatsapp-share i{
   font-size:20px;
    }
    
    .product-cart-box .cart-action-wrapper .add-to-cart-btn{
        font-size: 11px;
        margin-right: 0px;
    }
    
    .View-simple a{
            font-size: 8px;
           
    }
    
    .main-mobile img{
   /*height: 380px !important;*/
   object-position: center;
   
    }
    
  .left{
      gap: 0px !important;
  }
  
  #thumbs img{
    width: 100px !important;
    height: 100px !important;
  }
  
  .thumbs-wrapper{
          margin-top: 8px;
  }
  

  
      .right .title {
        font-size: 1.5em;
      }
    
}


@media(max-width:991px) {

    .cart-action-wrapper {
        gap: 0px;
    }

    .stage img {
        border-radius: unset;
    }

    .pproduct-details {
        padding: 0px
    }

}
</style>
<!--Quantoty Code -->
<script>
var productStock = {{ $product->stock }};
var productName = "{{ $product->name }}";
</script>
<!--end Quantity Code -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ============================================
    // 1. REVIEW AUTHENTICATION & INTENTION HANDLING
    // ============================================
    const openReviewBtn = document.getElementById('openReviewBtn');
    const reviewOverlay = document.getElementById('reviewOverlay');
    const closePopupBtn = document.getElementById('closePopup');

    // Check if user is logged in (from Laravel)
    //const isLoggedIn = {{ auth()->check() && auth()->user()->type == 2 ? 'true' : 'false' }};
    const isLoggedIn = {{ auth()->guard('customer')->check() ? 'true' : 'false' }};
const productId = {{ $product->id }};

    // Variables to track review intention
    let intendedReview = false;

    // Function to open review popup
    function openReviewPopup() {
        if (!reviewOverlay) return;
        reviewOverlay.style.display = 'flex';
        document.body.style.overflow = 'hidden'; // Prevent scrolling
    }

    // Function to close review popup
    function closeReviewPopup() {
        if (!reviewOverlay) return;
        reviewOverlay.style.display = 'none';
        document.body.style.overflow = ''; // Restore scrolling
    }

    // Open Review Button Click Handler
    if (openReviewBtn) {
        openReviewBtn.addEventListener('click', function(e) {
            e.preventDefault();

            if (isLoggedIn) {
                // If logged in, open review popup directly
                openReviewPopup();
            } else {
                // If not logged in, set intention and open login modal
                intendedReview = true;

                // Store product ID in sessionStorage for after login
                sessionStorage.setItem('intendedReviewProductId', productId);
                sessionStorage.setItem('intendedReviewAction', 'open');

                // Open login modal
                const loginLink = document.querySelector('.open-modal[data-modal="loginModalTop"]');
                if (loginLink) {
                    loginLink.click();
                } else {
                    // Fallback if modal link not found
                    const loginModal = document.getElementById('loginModalTop');
                    if (loginModal) {
                        loginModal.style.display = 'block';
                    }
                }
            }
        });
    }

    // Close Review Popup
    if (closePopupBtn) {
        closePopupBtn.addEventListener('click', closeReviewPopup);
    }

    // Close when clicking outside the popup
    if (reviewOverlay) {
        reviewOverlay.addEventListener('click', function(e) {
            if (e.target === reviewOverlay) {
                closeReviewPopup();
            }
        });
    }

    // Check if we need to open review popup after login (on page load)
    function checkAndOpenReviewPopup() {
        const intendedProductId = sessionStorage.getItem('intendedReviewProductId');
        const intendedAction = sessionStorage.getItem('intendedReviewAction');

        // Check if user is logged in AND has intended to write a review
        // AND the intended product ID matches current product
        if (isLoggedIn && intendedProductId && intendedAction === 'open' &&
            parseInt(intendedProductId) === productId) {

            // Open review popup after a short delay
            setTimeout(() => {
                openReviewPopup();

                // Clear the intention
                sessionStorage.removeItem('intendedReviewProductId');
                sessionStorage.removeItem('intendedReviewAction');
                intendedReview = false;
            }, 800);
        }
    }

    // Run check on page load
    checkAndOpenReviewPopup();

    // ============================================
    // 2. REVIEW FORM FUNCTIONALITY
    // ============================================

    /* ⭐ STAR RATING */
    const ratingStars = document.querySelectorAll(".star");
    const ratingInput = document.getElementById("rating");

    if (ratingStars.length && ratingInput) {
        ratingStars.forEach(star => {
            star.addEventListener("click", function() {
                let value = this.dataset.value;
                ratingInput.value = value;

                // Update star display
                ratingStars.forEach(s => {
                    s.classList.toggle("active", s.dataset.value <= value);
                });
            });
        });

        // Set default rating to 5
        if (!ratingInput.value) {
            ratingInput.value = 5;
            ratingStars.forEach(s => {
                s.classList.toggle("active", s.dataset.value <= 5);
            });
        }
    }

    /* 📸 IMAGE PREVIEW */
    const reviewImagesInput = document.getElementById("reviewImages");
    if (reviewImagesInput) {
        reviewImagesInput.addEventListener("change", function() {
            let box = document.getElementById("imagePreviewBox");
            if (!box) {
                box = document.createElement("div");
                box.id = "imagePreviewBox";
                box.style.marginTop = "10px";
                this.after(box);
            }
            box.innerHTML = "";

            [...this.files].forEach(file => {
                let img = document.createElement("img");
                img.src = URL.createObjectURL(file);
                img.style.maxWidth = "80px";
                img.style.margin = "5px";
                img.style.borderRadius = "5px";
                img.style.border = "1px solid #ddd";
                box.appendChild(img);
            });
        });
    }

    /* 📤 FORM SUBMIT */
    const reviewForm = document.getElementById("reviewFormCustom");
    if (reviewForm) {
        reviewForm.addEventListener("submit", function(e) {
            e.preventDefault();

            // Check if user is logged in
            if (!isLoggedIn) {
                // Show error and prompt login
                const formErrors = document.getElementById("formErrors");
                if (formErrors) {
                    formErrors.innerHTML =
                        '<div class="alert alert-danger">Please login to submit a review.</div>';
                    formErrors.style.display = 'block';
                }

                // Set intention and open login modal
                intendedReview = true;
                sessionStorage.setItem('intendedReviewProductId', productId);
                sessionStorage.setItem('intendedReviewAction', 'open');

                closeReviewPopup();

                // Open login modal
                setTimeout(() => {
                    const loginLink = document.querySelector(
                        '.open-modal[data-modal="loginModalTop"]');
                    if (loginLink) {
                        loginLink.click();
                    }
                }, 800);

                return;
            }

            // Validate required fields
            const name = document.querySelector('input[name="name"]');
            const rating = document.getElementById('rating');
            const reviewText = document.querySelector('textarea[name="review"]');

            if (!name.value.trim() || !rating.value || !reviewText.value.trim()) {
                const formErrors = document.getElementById("formErrors");
                if (formErrors) {
                    formErrors.innerHTML =
                        '<div class="alert alert-danger">Please fill in all required fields.</div>';
                    formErrors.style.display = 'block';
                }
                return;
            }

            // Show loading state
            const submitBtn = this.querySelector('.btn-submit-review');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.innerHTML =
                '<span class="spinner-border spinner-border-sm"></span> Submitting...';

            // Clear previous messages
            const formErrors = document.getElementById("formErrors");
            const formSuccess = document.getElementById("formSuccess");
            if (formErrors) formErrors.style.display = 'none';
            if (formSuccess) formSuccess.style.display = 'none';

            // Prepare form data
            let formData = new FormData(this);

            // Add CSRF token if not already included
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                formData.append('_token', csrfToken.getAttribute('content'));
            }

            // Send AJAX request
            fetch("{{ route('submit.review') }}", {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        "Accept": "application/json"
                    }
                })
                .then(res => {
                    if (!res.ok) {
                        throw new Error(`HTTP error! status: ${res.status}`);
                    }
                    return res.json();
                })
                .then(data => {
                    if (data.success) {
                        // Show success message
                        if (formSuccess) {
                            formSuccess.innerHTML =
                                `<div class="alert alert-success">${data.message}</div>`;
                            formSuccess.style.display = 'block';
                        }

                        // Clear form
                        this.reset();

                        // Reset stars
                        if (ratingStars.length) {
                            ratingStars.forEach(s => s.classList.remove('active'));
                            ratingInput.value = '';
                        }

                        // Clear image preview
                        const imagePreviewBox = document.getElementById("imagePreviewBox");
                        if (imagePreviewBox) {
                            imagePreviewBox.innerHTML = '';
                        }

                        // Reload page after delay to show new review
                        setTimeout(() => {
                            location.reload();
                        }, 800);
                    } else {
                        // Show validation errors
                        if (formErrors) {
                            let errorHtml = '<div class="alert alert-danger">';
                            if (data.errors) {
                                const errors = Object.values(data.errors).flat();
                                errorHtml += errors.join('<br>');
                            } else {
                                errorHtml += data.message || 'Submission failed. Please try again.';
                            }
                            errorHtml += '</div>';
                            formErrors.innerHTML = errorHtml;
                            formErrors.style.display = 'block';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (formErrors) {
                        formErrors.innerHTML =
                            '<div class="alert alert-danger">Something went wrong. Please try again.</div>';
                        formErrors.style.display = 'block';
                    }
                })
                .finally(() => {
                    // Reset button
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                });
        });
    }
});
</script>




<!-- seclect size end -->


<script>
// WhatsApp Share
function shareOnWhatsApp() {
    let url = window.location.href;
    let text = "Check this out: ";
    let whatsappURL = "https://api.whatsapp.com/send?text=" + encodeURIComponent(text + url);
    window.open(whatsappURL, "_blank");
}

// Facebook Share
function shareOnFacebook() {
    let url = window.location.href;
    let facebookURL = "https://www.facebook.com/sharer/sharer.php?u=" + encodeURIComponent(url);
    window.open(facebookURL, "_blank");
}

// Copy Link with image
function copyBlogLinkWithImage() {
    let url = window.location.href;
    navigator.clipboard.writeText(url).then(() => {
        alert("Link Copied!");
    }).catch(err => {
        console.log("Copy failed", err);
    });
}
</script>


<!-- discrtipstion 20 word aayega start -->
<script>
document.addEventListener("DOMContentLoaded", function() {

    let desc = document.getElementById("descText");
    let btn = document.getElementById("seeMoreBtn");

    let words = desc.innerText.split(" ");

    if (words.length <= 20) {
        btn.style.display = "none";
        return;
    }

    let shortText = words.slice(0, 20).join(" ");
    let fullText = desc.innerHTML;

    desc.innerText = shortText + "...";

    btn.addEventListener("click", function() {

        if (btn.innerText === "See More") {
            desc.innerHTML = fullText;
            btn.innerText = "See Less";
        } else {
            desc.innerText = shortText + "...";
            btn.innerText = "See More";
        }

    });

});
</script>
<!-- discrtipstion 20 word aayega end -->

<style>
/* Overlay */
.review-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.6);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

/* Popup Box */
.review-popup {
    background: #fff;
    width: 420px;
    max-width: 95%;
    border-radius: 12px;
    padding: 20px;
    position: relative;
    animation: fadeInUp 0.3s ease;
}



/* Title */
.review-title {
    text-align: center;
    font-weight: 600;
}

/* Form */
.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    font-size: 14px;
    margin-bottom: 5px;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
}

/* Stars */
.rating-stars {
    display: flex;
    gap: 6px;
    font-size: 22px;
    cursor: pointer;
}

.rating-stars .star {
    color: #ccc;
    transition: color 0.2s;
}

.rating-stars .star.active {
    color: #ffc107;
}

/* Submit Button */
.btn-submit-review {
    width: 100%;
    padding: 10px;
    border: none;
    background: #a51235;
    color: #fff;
    font-size: 15px;
    border-radius: 6px;
    cursor: pointer;
    margin-top: 10px;
}




/* Messages */
#formErrors {
    color: red;
    margin-top: 10px;
    font-size: 13px;
}

#formSuccess {
    color: green;
    margin-top: 10px;
    font-size: 13px;
}

/* Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.all-reviews-btn {
    display: flex;
    margin-top: 10px;
    color: #1a1a18;
    font-weight: 600;
    text-decoration: underline;
    justify-content: center;
    align-items: center;
}



/* =========================
   IMAGE POPUP & ZOOM SYSTEM
========================= */

/* Popup Container */
#popup {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.95);
    z-index: 9999;
    display: none;
    justify-content: center;
    align-items: center;
}

#popup.active {
    display: flex;
}

/* Popup Content */
#popupContent {
    width: 100%;
    height: 100%;
    position: relative;
}

/* Main Image Wrapper */
#popupMainWrapper {
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;
    touch-action: none;
}

/* Main Image */
#popupMain {
    max-width: 100%;
    max-height: 80vh;
    transform-origin: center center;
    will-change: transform;
    transition: transform 0.25s ease;
    user-select: none;
    -webkit-user-select: none;
    -webkit-touch-callout: none;
    -webkit-tap-highlight-color: transparent;
}

/* Mobile Touch Optimizations */
@media (max-width: 1024px) {
    #popupMain {
        transition: transform 0.2s ease-out;
    }
}

/* Thumbnails */
#popupThumbs {
    position: absolute;
    bottom: 20px;
    inset-inline: 0;
    display: flex;
    justify-content: center;
    gap: 10px;
    padding: 10px;
    overflow-x: auto;
    background: rgba(0, 0, 0, 0.5);
}

#popupThumbs img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border: 2px solid transparent;
    border-radius: 5px;
    cursor: pointer;
}

#popupThumbs img.active {
    border-color: #fff;
}

/* Close Button */
.popup-close {
    position: fixed;
    top: 20px;
    right: 20px;
    background: #a51235;
    color: #fff;
    border: none;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    font-size: 24px;
    z-index: 10001;
    cursor: pointer;
    display: none;
}

#popup.active .popup-close {
    display: block;
}

/* =========================
   IMAGE POPUP & ZOOM SYSTEM
========================= */

/* Popup Container */
#popup {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.95);
    z-index: 9999;
    display: none;
    justify-content: center;
    align-items: center;
}

#popup.active {
    display: flex;
}

/* Popup Content */
#popupContent {
    width: 100%;
    height: 100%;
    position: relative;
}

/* Main Image Wrapper */
#popupMainWrapper {
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;
    touch-action: none;
}

/* Main Image */
#popupMain {
    max-width: 100%;
    max-height: 80vh;
    transform-origin: center center;
    will-change: transform;
    transition: transform 0.25s ease;
    user-select: none;
    -webkit-user-select: none;
    -webkit-touch-callout: none;
    -webkit-tap-highlight-color: transparent;
}

/* Mobile Touch Optimizations */
@media (max-width: 1024px) {
    #popupMain {
        transition: transform 0.2s ease-out;
    }
}

/* Thumbnails */
#popupThumbs {
    position: absolute;
    bottom: 20px;
    inset-inline: 0;
    display: flex;
    justify-content: center;
    gap: 10px;
    padding: 10px;
    overflow-x: auto;
    background: rgba(0, 0, 0, 0.5);
}

#popupThumbs img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border: 2px solid transparent;
    border-radius: 5px;
    cursor: pointer;
}

#popupThumbs img.active {
    border-color: #fff;
}

/* Close Button */
.popup-close {
    position: fixed;
    top: 20px;
    right: 20px;
    background: #a51235;
    color: #fff;
    border: none;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    font-size: 24px;
    z-index: 10001;
    cursor: pointer;
    display: none;
}

#popup.active .popup-close {
    display: block;
}

/* =========================
   DESKTOP IMAGE ZOOM LENS
========================= */

/* Image Stage */
.stage {
    position: relative;
    display: inline-block;
}

/* Main Product Image */
#mainImg {
    display: block;
    position: relative;
    z-index: 1;
}

/* Zoom Lens */
#lens {
    position: absolute;
    width: 120px;
    height: 120px;
    background: rgba(255, 255, 255, 0.3);
    border: 2px solid #c5c5c5;
    pointer-events: none;
    display: none;
    z-index: 2;
}

/* Zoom Result Box */
#zoomResult {
    display: none;
    background-repeat: no-repeat;
}

/* Desktop only – JS will control lens */
@media (min-width: 1025px) {
    #lens {
        display: none;
    }
}

/* Mobile / Tablet – disable zoom */
@media (max-width: 1024px) {

    #lens,
    #zoomResult {
        display: none !important;
    }
}
</style>


@include('userfooter')