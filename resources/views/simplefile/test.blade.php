@include('userheader')

<div class="wishlist-page">
    @foreach($wishlists as $wishlist)

    <div class="wish-row">

        <div class="wish-left">
            <img src="{{ url('userassets/image/product/'.$wishlist->product->image) }}">
            <div>
                <h5>{{ $wishlist->product->name }}</h5>
            </div>
        </div>

        <div class="wish-right">
            <div class="price">₹{{ $wishlist->product->display_price }}</div>

            <a href="{{ route('product.details', $wishlist->product->slug) }}" class="btn-view">
                View Product
            </a>
        </div>

    </div>

    @endforeach
</div>


<style>
.wishlist-page {
    width: 50%;
    margin: 30px auto;
}

/* full row */
.wish-row {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #fff;
    padding: 15px 15px;
    margin-bottom: 12px;
    border-radius: 10px;
    border: 1px solid #d3cfcf;
}

/* left */
.wish-left {
    display: flex;
    align-items: center;
    gap: 15px;
}

.wish-left img {
    width: 85px;
    height: 100px;
    object-fit: cover;
    border-radius: 8px;
}

/* right */
.wish-right {
    display: flex;
    align-items: center;
    gap: 20px;
}

/* price */
.price {
    font-size: 18px;
    font-weight: 700;
    color: #cb7000;
}

/* button */
.btn-view {
    padding: 8px 18px;
    background: #cb7000;
    color: #fff;
    border-radius: 6px;
    text-decoration: none;
    transition: .3s;
}

.wish-left h5 {
    text-transform: uppercase;
    font-size: 18px;
    letter-spacing: 1px;
}

.btn-view:hover {
    background: #181724;
    color: white;
}

/* mobile */
@media(max-width:576px) {
    .wish-row {
        flex-direction: column;
        gap: 10px;
        text-align: center;
    }

    .wish-right {
        flex-direction: column;
    }
}

@media(max-width:991px) {
    .wishlist-page {
        width: 100%;
    }
}
</style>

@include('userfooter')