@php
// Safe access to variables
$products = $products ?? [];


// Wishlist data
$guestToken = Cookie::get('guest_token');
$wishlistProducts = \App\Models\Wishlist::where('guest_token', $guestToken)->pluck('product_id')->toArray();
@endphp

@if($products->count() > 0)
@foreach($products as $product)


<div class="product-card">
    <!-- Your product card HTML -->
    <a href="{{ url('product/' . $product->slug) }}" class="product-image {{ empty($por->image2) ? 'single-image' : 'double-image' }}">
        <img src="{{ url('userassets/image/product/' . $product->image) }}" alt="{{ $product->name }}" class="front">
        @if($product->image2)
        <img src="{{ url('userassets/image/product/' . $product->image2) }}" alt="{{ $product->name }}" class="back">
        @endif

        @if($product->stock > 0)
        <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form"
            data-product-id="{{ $product->id }}">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="name" value="{{ $product->name }}">
            <input type="hidden" name="price" value="{{ $product->display_price ?? $product->mrp_price }}">
            <input type="hidden" name="mrp_price" value="{{ $product->mrp_price }}">
            <input type="hidden" name="discount" value="{{ $product->discount ?? 0 }}">
            <input type="hidden" name="qty" value="1">

            <input type="hidden" name="image" value="{{ $product->image }}">

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

    @if(!empty($product->discount) && $product->discount > 0)
    <span class="discount-badge">{{ $product->discount }}%</span>
    @endif

    <i class="bi heart-icon {{ in_array($product->id, $wishlistProducts) ? 'bi-heart-fill text-danger' : 'bi-heart' }}"
        data-product-id="{{ $product->id }}" onclick="toggleWishlist(this, {{ $product->id }})"></i>



    <p class="product-name">
        <a href="{{ url('product/' . $product->slug) }}" class="product-dotss">{{ $product->name }}</a>
    </p>


    <div class="skucombind">
        <p class="product-sku">
            <a href="{{ url('product/' . $product->slug) }}">ISBN:{{ $product->sku }}</a>
        </p>
        @if($product->total_reviews > 0)
        <div class="product-rating">
            @php
            $avgRating = $product->avg_rating;
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
                        <span class="review-count">({{ $product->total_reviews }})</span>
            </div>
        </div>
        @endif
    </div>

    <div class="product-price">
        @if(!empty($product->display_price) && $product->display_price != $product->mrp_price)
        <p class="current-price">
            <i class="bi bi-currency-rupee"></i>{{ $product->display_price }}
            <span class="text-decoration-line-through text-muted">
                <i class="bi bi-currency-rupee"></i>{{ $product->mrp_price }}
            </span>

        </p>
        @else
        <p class="current-price">
            <i class="bi bi-currency-rupee"></i>{{ $product->mrp_price }}
        </p>
        @endif
    </div>
</div>

@endforeach
@else
<div class="no-products">
    <p>No products found matching your criteria.</p>
    <button onclick="clearAllFilters()" class="btn-clear">Clear Filters</button>
</div>
@endif

@if($products->hasPages())
<div class="pagination-wrapper">
    {!! $products->onEachSide(1)->links('pagination::bootstrap-4') !!}
</div>
@endif
<style>
.pagination-wrapper {
    grid-column: 1 / -1;
    /* 👈 FULL ROW */
    display: flex;
    justify-content: center;
    align-items: center;

}
</style>