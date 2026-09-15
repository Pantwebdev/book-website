<div class="row">
    @php
    $guestToken = Cookie::get('guest_token');
    $wishlistProducts = \App\Models\Wishlist::where('guest_token', $guestToken)
    ->pluck('product_id')->toArray();
    @endphp
    @foreach($bestseller as $por)
    <div class="col-6 col-sm-4 col-md-3 col-lg-5th">

        <div class="product-card">

            <a href="{{ url('product/' . $por->slug) }}" class="product-image {{ empty($por->image2) ? 'single-image' : 'double-image' }}">
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

<div class="pagination-wrapper">
    {!! $bestseller->links('pagination::bootstrap-5') !!}
</div>