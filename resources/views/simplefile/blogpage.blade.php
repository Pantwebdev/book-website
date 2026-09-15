@include('userheader')
<div class="blog-banner">
    <img src="{{ url('userassets/image/' . $banner->image) }}" alt="Blog Banner">    
    <div class="banner-text">
        <h1>Our Blog</h1>
        <p>Latest articles, updates and stories</p>
    </div>
</div>


<div class="blog-section">
    <div id="blog-list">
        @include('partials.blog-list')
    </div>

    <div class="ajax-load text-center" style="display:none">
        <p >Loading...</p>
    </div>
</div>

@include('userfooter')


<script>
$(document).ready(function () {
    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        let page = $(this).attr('href').split('page=')[1];
        fetchBlogs(page);
    });

    function fetchBlogs(page) {
        $.ajax({
            url: "/blog?page=" + page,
            success: function (data) {
                $("#blog-list").html(data);
            }
        });
    }
});
</script>
<style>
  .blog-banner {
    position: relative;
    width: 100%;
    height: 300px;
    overflow: hidden;
    margin-bottom: 30px;
}

.blog-banner img {
    width: 100%;
    height: 100%;
    object-fit: cover; /* Full banner cover */
    filter: brightness(70%);
}

.banner-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: #fff;
    text-align: center;
}

.banner-text h1 {
    font-size: 42px;
    font-weight: 700;
    margin-bottom: 10px;
}

.banner-text p {
    font-size: 18px;
    opacity: 0.9;
}
.blogcard {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    padding: 0px 20px;
}

.card-blog {
    /* background: #e3e1e1; */
    border-radius: 10px;
    padding: 10px;
    box-shadow: 0 0 10px #a3a2a2;
}

.card-blog img {
    width: 100%;
    object-fit: cover;    /* Image fully visible, no crop */
    border-radius: 8px;
    display: block;
    min-height: 418px
}

.card-blog .blog-title {
    font-size:18px;
    text-align: left;
    margin-bottom: 2px;
    margin-top: 10px;
}

.card-blog p:first-of-type{
    margin:0px
}
@media (max-width: 992px) {   /* Tablet */
    .blogcard {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 576px) {   /* Mobile */
    .blogcard {
        grid-template-columns: repeat(1, 1fr);
    }
}


</style>