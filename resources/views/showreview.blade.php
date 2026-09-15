       @include('userheader')






    <div class="container my-4">

    <h2 class="mb-3">
        Reviews for <strong>{{ $product->title }}</strong>
    </h2>

    @if($reviews->count() > 0)

        <div class="row">
            @foreach($reviews as $review)
                <div class="col-12 col-md-4 col-lg-3 mb-4">

                    <div class="review-card ">

                        <div class="review-header">
                            <strong>{{ $review->name }}</strong>

                            <div class="review-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="{{ $i <= $review->rating ? 'active' : '' }}">★</span>
                                @endfor
                            </div>
                        </div>

                       <p class="review-text clamp-3">
                        {{ $review->review }}
                      </p>

                    <a href="javascript:void(0)" class="read-more d-none">
                        Read more
                    </a>

                        @if($review->image)
                            <img src="{{ url('userassets/image/reviews/' . $review->image) }}"
                                 class="review-image img-fluid">
                        @endif

                        <p class="text-muted">
                            {{ $review->created_at->format('d M Y') }}
                         </p>

                    </div>

                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-3">
            {{ $reviews->links() }}
        </div>

    @else
        <p>No reviews found for this product.</p>
    @endif

</div>


</div>


       @include('userfooter')
       <style>
        .review-card{
    border: 1px solid #eee;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 15px;
}

.review-header{
    display: flex;
    justify-content: left;
    align-items: center;
    margin-bottom: 8px;
    gap:10px
}
.review-header strong{
    text-transform: capitalize;
}
.review-rating span{
    color: #ccc;
    font-size: 18px;
}

.review-rating span.active{
    color: #ffc107;
}

.review-text{
   font-size: 14px;
    line-height: 20px;
    letter-spacing: 0.5px;
    color: #1a1a1a;
}
.clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;   /* 👈 sirf 3 line */
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin-bottom:0px
}
.read-more {
    font-size: 14px;
    color: #0d6efd;
    cursor: pointer;
    display: inline-block;
   
}

.review-image{
    max-width: 100%;
    border-radius: 6px;
    margin: 8px 0;
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll(".review-text").forEach((text) => {

        const readMore = text.nextElementSibling;

        // Agar text 3 lines se zyada hai
        if (text.scrollHeight > text.clientHeight) {
            readMore.classList.remove("d-none");
        }

        readMore.addEventListener("click", function () {

            if (text.classList.contains("clamp-3")) {
                text.classList.remove("clamp-3");
                readMore.textContent = "Read less";
            } else {
                text.classList.add("clamp-3");
                readMore.textContent = "Read more";
            }

        });

    });

});
</script>
