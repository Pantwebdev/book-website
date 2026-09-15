@include('userheader')
<!-- slider selction start -->

<section>
    <div class="owl-carousel owl-theme homeslider">
        @foreach($Homeslider as $index=>$hs)
        <div class="item"> <a href="{{ !empty($hs->url) ? $hs->url : url('new-arrivals') }}">
                <img src="{{ url('userassets/image/slider/' . $hs->image) }}" alt="">
            </a></div>

        @endforeach
    </div>
</section>






<section class="categories  ">
    <div class="container-fluid">
        <div class="row g-3">

            @foreach($categories->take(4) as $cat)
            <div class="col-6 col-sm-6 col-md-6 col-lg-3">
                <div class="category-box">
                    <a href="{{ $cat->category ? url($cat->category->slug.'/'.$cat->slug) : '#' }}">
                        <img src="{{ url('userassets/image/menu/' . $cat->image) }}" alt="{{ $cat->title }}"
                            class="img-fluid w-100">

                        <div class="category-text">
                            @php
                            $words = explode(' ', strtoupper($cat->title));
                            $first = $words[0] ?? '';
                            $rest = implode(' ', array_slice($words, 1));
                            @endphp

                            @if($rest)
                            <span class="small-text">{{ $first }}</span>
                            <span class="big-text">{{ $rest }}</span>
                            @else
                            <span class="big-text">{{ $first }}</span>
                            @endif
                        </div>
                    </a>
                </div>
            </div>
            @endforeach

        </div>
    </div>
</section>







<!-- New Arrivals section start -->
<section class=" arival-main " style="background-color: #cb700014; ">


    <div class="heading-sectionnew">
        <div class="title">New Arrivals</div>

        <div class="dividerswction">
            <img src="{{url('userassets/image/flowericon-img.png')}}" alt="decorative icon">
        </div>
    </div>

    <!-- Arrival slider -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="owl-carousel arrivals-slider">
                    <!-- Product 1 -->
                    @php
                    $guestToken = Cookie::get('guest_token');
                    $wishlistProducts = \App\Models\Wishlist::where('guest_token', $guestToken)
                    ->pluck('product_id')->toArray();
                    @endphp
                    @foreach($newarrivals as $index=>$na)
                    <div class="item">
                        <div class="product-card ">
                            <a href="{{ url('product/' . $na->slug) }}"
                                class="product-image {{ empty($na->image2) ? 'single-image' : 'double-image' }}">
                                <!-- Front & Back images -->
                                <img src="{{ url('userassets/image/product/' . $na->image) }}" alt="Product 1"
                                    class="front">
                                @if(!empty($na->image2))
                                <img src="{{ url('userassets/image/product/' . $na->image2) }}" alt="Product 1"
                                    class="back">
                                @endif

                                <!-- Add button -->
                                @if($na->stock > 0)
                                <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form"
                                    data-product-id="{{ $na->id }}">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $na->id }}">
                                    <input type="hidden" name="name" value="{{ $na->name }}">
                                    <input type="hidden" name="price" id="formPrice" value="{{ $na->display_price }}">
                                    <input type="hidden" name="mrp_price" id="formMrpPrice"
                                        value="{{ $na->mrp_price }}">
                                    <input type="hidden" name="discount" id="formDiscount" value="{{ $na->discount }}">
                                    <input type="hidden" name="qty" id="formQty" value="1">

                                    <input type="hidden" name="image" value="{{ $na->image }}">

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

                            <!-- Top-left badge -->
                            @if(!empty($na->discount) && $na->discount > 0)
                            <span class="discount-badge">{{$na->discount}}%</span>

                            @endif

                            <!-- Top-right heart icon -->
                            <i class="bi heart-icon {{ in_array($na->id, $wishlistProducts) ? 'bi-heart-fill text-danger' : 'bi-heart' }}"
                                data-product-id="{{ $na->id }}" onclick="toggleWishlist(this, {{ $na->id }})"></i>




                            <!-- Product title & price -->
                            <p><a href="{{ url('product/' . $na->slug) }}" class="product-dotss">{{$na->name}}</a></p>
                            <!-- <p>{{$na->name}}</p> -->

                            <div class="skucombind">
                                <p><a href="{{ url('product/' . $na->slug) }}">ISBN:{{$na->sku}}</a></p>
                                <!-- ✅ RATING SECTION -->
                                @if($na->total_reviews > 0)
                                <div class="product-rating">
                                    @php
                                    $avgRating = $na->avg_rating;
                                    $totalReviews = $na->total_reviews;

                                    // Calculate stars display
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
                                                @if($totalReviews > 0)
                                                <span class="rating-number">{{ number_format($avgRating, 1) }}</span>
                                                <span class="review-count">({{ $totalReviews }})</span>
                                                @else
                                                <span class="no-reviews">No ratings</span>
                                                @endif
                                    </div>
                                </div>
                                @endif
                            </div>



                            <div class="product-price">
                                @if(!empty($na->display_price))
                                {{-- Discounted price --}}
                                <p>
                                    <span><i class="bi bi-currency-rupee"></i></span>{{ $na->display_price }}
                                    <span
                                        style="text-decoration: line-through; color: #888; margin-left: 5px; font-size:12px;">
                                        <i class="bi bi-currency-rupee"></i>{{ $na->mrp_price }}
                                    </span>
                                </p>
                                @else
                                {{-- Only MRP --}}
                                <p>
                                    <span><i class="bi bi-currency-rupee"></i></span>{{ $na->mrp_price }}
                                </p>
                                @endif


                            </div>
                        </div>
                    </div>
                    @endforeach



                </div>
            </div>
        </div>
    </div>
    <a href="{{ url('/new-arrivals')}}" class="homepage-viewall"><button>View all</button></a>
</section>

<!-- New Arrivals section end -->


<!------------------------ Trending Right Now section start ----------------------------->
<section class="allbannerappy">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 p-0">
                <div class="trending-banner">
                    @if($banner1 && $banner1->image)
                    @if($banner1_link)
                    <a href="{{ $banner1_link }}">
                        <img src="{{ url('userassets/image/' . $banner1->image) }}" alt="Trending Right Now"
                            class="img-fluid w-100">
                    </a>
                    @else
                    <img src="{{ url('userassets/image/' . $banner1->image) }}" alt="Trending Right Now"
                        class="img-fluid w-100">
                    @endif
                    @endif
                </div>
                <div class="scroll-img-bottom">
                    <div class="scroll-track">
                        <img src="{{ url('userassets/image/scroll-img.png') }}" alt="">
                        <img src="{{ url('userassets/image/scroll-img.png') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!------------------------ Trending Right Now section end ----------------------------->


<!------------------------ Shop by Best seller start ----------------------------->


<section class=" arival-main">

    <div class="heading-sectionnew">
        <div class="title">Best seller</div>

        <div class="dividerswction">
            <img src="{{url('userassets/image/flowericon-img.png')}}" alt="decorative icon">
        </div>
    </div>


    <div class="container-fluid">
        <div class="row">

            @foreach($product_on_sale as $por)
            <div class="col-6 col-md-4 col-lg-custom">

                <div class="product-card">

                    <a href="{{ url('product/' . $por->slug) }}"
                        class="product-image {{ empty($por->image2) ? 'single-image' : 'double-image' }}">
                        <img src="{{ url('userassets/image/product/' . $por->image) }}" class="front">
                        @if(!empty($por->image2))
                        <img src="{{ url('userassets/image/product/' . $por->image2) }}" class="back">
                        @endif

                        @if($por->stock > 0)
                        <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form"
                            data-product-id="{{ $por->id }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $por->id }}">
                            <input type="hidden" name="name" value="{{ $por->name }}">
                            <input type="hidden" name="price" value="{{ $por->display_price }}">
                            <input type="hidden" name="mrp_price" value="{{ $por->mrp_price }}">
                            <input type="hidden" name="discount" value="{{ $por->discount }}">
                            <input type="hidden" name="qty" value="1">

                            <input type="hidden" name="image" value="{{ $por->image }}">

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

                    @if(!empty($por->discount) && $por->discount > 0)
                    <span class="discount-badge">{{$por->discount}}%</span>
                    @endif

                    <i class="bi heart-icon {{ in_array($por->id, $wishlistProducts) ? 'bi-heart-fill text-danger' : 'bi-heart' }}"
                        data-product-id="{{ $por->id }}" onclick="toggleWishlist(this, {{ $por->id }})"></i>




                    <p><a href="{{ url('product/' . $por->slug) }}" class="product-dotss">{{$por->name}}</a></p>


                    <div class="skucombind">
                        <p><a href="{{ url('product/' . $por->slug) }}">ISBN:{{$por->sku}}</a></p>


                        @if($por->total_reviews > 0)
                        <div class="product-rating">
                            @php
                            $avgRating = $por->avg_rating;
                            $totalReviews = $por->total_reviews;

                            // Calculate stars display
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
                                        @if($totalReviews > 0)
                                        <span class="rating-number">{{ number_format($avgRating, 1) }}</span>
                                        <span class="review-count">({{ $totalReviews }})</span>
                                        @else
                                        <span class="no-reviews">No ratings</span>
                                        @endif
                            </div>
                        </div>
                        @endif
                    </div>


                    <div class="product-price">
                        @if(!empty($por->display_price))
                        {{-- Discounted price --}}
                        <p>
                            <span><i class="bi bi-currency-rupee"></i></span>{{ $por->display_price }}
                            <span style="text-decoration: line-through; color: #888; margin-left: 5px;">
                                <i class="bi bi-currency-rupee"></i>{{ $por->mrp_price }}
                            </span>
                        </p>
                        @else
                        {{-- Only MRP --}}
                        <p>
                            <span><i class="bi bi-currency-rupee"></i></span>{{ $por->mrp_price }}
                        </p>
                        @endif


                    </div>
                </div>
            </div>
            @endforeach

        </div>
    </div>


    <style>
    @media (min-width: 992px) {
        .col-lg-5th {
            width: 20%;
            flex: 0 0 20%;
        }
    }
    </style>
    <a href="{{ url('/seller')}}" class="homepage-viewall"><button>View all</button></a>
</section>
<!------------------------ Shop by Best seller  end ----------------------------->



<!------------------------ banner saree section start ----------------------------->
<section class="allbannerappy home2ndbanner">

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                @if($banner2 && $banner2->image)
                <section class="banner2-section">
                    <div class="container-fluid p-0 m-0">
                        <div class="row">
                            <div class="col-12 p-0">
                                @if($banner2_link)
                                <a href="{{ $banner2_link }}">
                                    <img src="{{ url('userassets/image/' . $banner2->image) }}" alt="Banner 2"
                                        class="img-fluid w-100">
                                </a>
                                @else
                                <img src="{{ url('userassets/image/' . $banner2->image) }}" alt="Banner 2"
                                    class="img-fluid w-100">
                                @endif
                            </div>
                        </div>
                    </div>
                </section>
                @endif



                <div class="scroll-img-bottom">
                    <div class="scroll-track">
                        <img src="{{url('userassets/image/scroll-img.png')}}" alt="">
                        <img src="{{url('userassets/image/scroll-img.png')}}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!------------------------ banner saree Now section end ----------------------------->

<!------------------------ Shop By Trend start----------------------------->

<!-- New Arrivals section start -->
<section class=" arival-main">


    <div class="heading-sectionnew">
        <div class="title">Exam corner</div>

        <div class="dividerswction">
            <img src="{{url('userassets/image/flowericon-img.png')}}" alt="decorative icon">
        </div>
    </div>

    <!-- Arrival slider -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="owl-carousel arrivals-slider">
                    <!-- Product 1 -->
                    @php
                    $guestToken = Cookie::get('guest_token');
                    $wishlistProducts = \App\Models\Wishlist::where('guest_token', $guestToken)
                    ->pluck('product_id')->toArray();
                    @endphp
                    @foreach($ExamCorner as $index=>$ec)
                    <div class="item">
                        <div class="product-card">
                            <a href="{{ url('product/' . $ec->slug) }}"
                                class="product-image {{ empty($ec->image2) ? 'single-image' : 'double-image' }}">
                                <!-- Front & Back images -->
                                <img src="{{ url('userassets/image/product/' . $ec->image) }}" alt="Product 1"
                                    class="front">
                                @if(!empty($ec->image2))
                                <img src="{{ url('userassets/image/product/' . $ec->image2) }}" alt="Product 1"
                                    class="back">
                                @endif

                                <!-- Add button -->
                                @if($ec->stock > 0)
                                <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form"
                                    data-product-id="{{ $ec->id }}">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $ec->id }}">
                                    <input type="hidden" name="name" value="{{ $ec->name }}">
                                    <input type="hidden" name="price" id="formPrice" value="{{ $ec->display_price }}">
                                    <input type="hidden" name="mrp_price" id="formMrpPrice"
                                        value="{{ $ec->mrp_price }}">
                                    <input type="hidden" name="discount" id="formDiscount" value="{{ $ec->discount }}">
                                    <input type="hidden" name="qty" id="formQty" value="1">
                                    <input type="hidden" name="image" value="{{ $ec->image }}">

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

                            <!-- Top-left badge -->
                            @if(!empty($ec->discount) && $ec->discount > 0)
                            <span class="discount-badge">{{$ec->discount}}%</span>

                            @endif

                            <!-- Top-right heart icon -->
                            <i class="bi heart-icon {{ in_array($ec->id, $wishlistProducts) ? 'bi-heart-fill text-danger' : 'bi-heart' }}"
                                data-product-id="{{ $ec->id }}" onclick="toggleWishlist(this, {{ $ec->id }})"></i>





                            <!-- Product title & price -->
                            <p><a href="{{ url('product/' . $ec->slug) }}" class="product-dotss">{{$ec->name}}</a></p>
                            <!-- <p>{{$ec->name}}</p> -->

                            <div class="skucombind">
                                <p><a href="{{ url('product/' . $ec->slug) }}">ISBN:{{$ec->sku}}</a></p>
                                <!-- ✅ RATING SECTION -->
                                @if($ec->total_reviews > 0)
                                <div class="product-rating">
                                    @php
                                    $avgRating = $ec->avg_rating;
                                    $totalReviews = $ec->total_reviews;

                                    // Calculate stars display
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
                                                @if($totalReviews > 0)
                                                <span class="rating-number">{{ number_format($avgRating, 1) }}</span>
                                                <span class="review-count">({{ $totalReviews }})</span>
                                                @else
                                                <span class="no-reviews">No ratings</span>
                                                @endif
                                    </div>
                                </div>
                                @endif
                            </div>



                            <div class="product-price">
                                @if(!empty($ec->display_price))
                                {{-- Discounted price --}}
                                <p>
                                    <span><i class="bi bi-currency-rupee"></i></span>{{ $ec->display_price }}
                                    <span
                                        style="text-decoration: line-through; color: #888; margin-left: 5px; font-size:12px;">
                                        <i class="bi bi-currency-rupee"></i>{{ $ec->mrp_price }}
                                    </span>
                                </p>
                                @else
                                {{-- Only MRP --}}ec
                                <p>
                                    <span><i class="bi bi-currency-rupee"></i></span>{{ $ec->mrp_price }}
                                </p>
                                @endif


                            </div>
                        </div>
                    </div>
                    @endforeach



                </div>
            </div>
        </div>
    </div>
    <a href="{{ url('/exam-corner')}}" class="homepage-viewall"><button>View all</button></a>
</section>

<!-- New Arrivals section end -->



<section class=" arival-main" style="background-color: #cb700014; ">


    <div class="heading-sectionnew">
        <div class="title">Featured books</div>

        <div class="dividerswction">
            <img src="{{url('userassets/image/flowericon-img.png')}}" alt="decorative icon">
        </div>
    </div>

    <!-- Featured books -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="owl-carousel arrivals-slider">
                    <!-- Product 1 -->
                    @php
                    $guestToken = Cookie::get('guest_token');
                    $wishlistProducts = \App\Models\Wishlist::where('guest_token', $guestToken)
                    ->pluck('product_id')->toArray();
                    @endphp
                    @foreach($FeaturedBook as $index=>$fb)
                    <div class="item">
                        <div class="product-card">
                            <a href="{{ url('product/' . $fb->slug) }}"
                                class="product-image  {{ empty($fb->image2) ? 'single-image' : 'double-image' }}">
                                <!-- Front & Back images -->
                                <img src="{{ url('userassets/image/product/' . $fb->image) }}" alt="Product 1"
                                    class="front">
                                @if(!empty($fb->image2))
                                <img src="{{ url('userassets/image/product/' . $fb->image2) }}" alt="Product 1"
                                    class="back">
                                @endif

                                <!-- Add button -->
                                @if($fb->stock > 0)
                                <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form"
                                    data-product-id="{{ $fb->id }}">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $fb->id }}">
                                    <input type="hidden" name="name" value="{{ $fb->name }}">
                                    <input type="hidden" name="price" id="formPrice" value="{{ $fb->display_price }}">
                                    <input type="hidden" name="mrp_price" id="formMrpPrice"
                                        value="{{ $fb->mrp_price }}">
                                    <input type="hidden" name="discount" id="formDiscount" value="{{ $fb->discount }}">
                                    <input type="hidden" name="qty" id="formQty" value="1">

                                    <input type="hidden" name="image" value="{{ $fb->image }}">

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

                            <!-- Top-left badge -->
                            @if(!empty($fb->discount) && $fb->discount > 0)
                            <span class="discount-badge">{{$fb->discount}}%</span>

                            @endif

                            <!-- Top-right heart icon -->
                            <i class="bi heart-icon {{ in_array($fb->id, $wishlistProducts) ? 'bi-heart-fill text-danger' : 'bi-heart' }}"
                                data-product-id="{{ $fb->id }}" onclick="toggleWishlist(this, {{ $fb->id }})"></i>




                            <!-- Product title & price -->
                            <p><a href="{{ url('product/' . $fb->slug) }}" class="product-dotss">{{$fb->name}}</a></p>
                            <!-- <p>{{$fb->name}}</p> -->

                            <div class="skucombind">
                                <p><a href="{{ url('product/' . $fb->slug) }}">ISBN:{{$fb->sku}}</a></p>
                                <!-- ✅ RATING SECTION -->
                                @if($fb->total_reviews > 0)
                                <div class="product-rating">
                                    @php
                                    $avgRating = $fb->avg_rating;
                                    $totalReviews = $fb->total_reviews;

                                    // Calculate stars display
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
                                                @if($totalReviews > 0)
                                                <span class="rating-number">{{ number_format($avgRating, 1) }}</span>
                                                <span class="review-count">({{ $totalReviews }})</span>
                                                @else
                                                <span class="no-reviews">No ratings</span>
                                                @endif
                                    </div>
                                </div>
                                @endif
                            </div>



                            <div class="product-price">
                                @if(!empty($fb->display_price))
                                {{-- Discounted price --}}
                                <p>
                                    <span><i class="bi bi-currency-rupee"></i></span>{{ $fb->display_price }}
                                    <span
                                        style="text-decoration: line-through; color: #888; margin-left: 5px; font-size:12px;">
                                        <i class="bi bi-currency-rupee"></i>{{ $fb->mrp_price }}
                                    </span>
                                </p>
                                @else
                                {{-- Only MRP --}}
                                <p>
                                    <span><i class="bi bi-currency-rupee"></i></span>{{ $fb->mrp_price }}
                                </p>
                                @endif


                            </div>
                        </div>
                    </div>
                    @endforeach



                </div>
            </div>
        </div>
    </div>
    <a href="{{ url('/featured')}}" class="homepage-viewall"><button>View all</button></a>
</section>

<!------------------------ Featured books end ----------------------------->
<!------------------------ customer blog  start ----------------------------->



<section class=" blog">
    <div class="heading-sectionnew">
        <div class="title" style="margin-bottom:20px"> Blog</div>

        <div class="dividerswction">
                <img src="{{url('userassets/image/flowericon-img.png')}}" alt="decorative icon">
            </div>
    </div>
    <div class="container-fluid">
        <div class="row g-3">

            @foreach($BlogData as $blog)

            <div class="col-6 col-md-6 col-lg-3 mb-4">

                <a href="{{ url('blog/'.$blog->slug) }}" class="blog-card">

                    <div class="royal-frame explore-text-sub">
                        <img class="collection-main-img" src="{{ url('userassets/image/blog/'.$blog->image) }}"
                            alt="{{ $blog->title }}">
                    </div>

                    <div class="homepage-blog" style="border: 1px solid #cdcdcd; padding: 5px;border-radius: 0px 0px 10px 10px;">
                        <h3 class="product-dotss">{{ $blog->title }}</h3>
                        <p class="blog-excerpt">
                            {{ Str::limit(strip_tags($blog->content), 35) }}
                        </p>
                    </div>

                </a>

            </div>

            @endforeach
        </div>
    </div>
    </div>




    <!------------------------ customer blog  end ----------------------------->


    <style>
    @media (max-width: 353px) {
        .product-rating .stars {
            font-size: 0.4rem !important;
        }
    }

    /* @media (max-width: 500px) {
        .product-card a img {
            height: 155px !important;
        }
    } */

    .product-rating .stars {
        font-size: 0.4rem;
    }

    /* @media (max-width: 576px) {
        .owl-carousel .item img {
            height: 200px;
            object-position: 0% 0%;
        }
    } */
    </style>

    @include('userfooter')