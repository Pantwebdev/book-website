@include('userheader')
<div class="blog-banner">
    <img src="{{ url('userassets/image/blog/'.$blog->image) }}" alt="Blog Banner">
    <div class="banner-text">
        <h1>{{ $blog->title }}</h1>
        {!! $blog->content !!}
    </div>
</div>
@include('userfooter')

<style>
.blog-banner img {
    width: 100%;
}

.banner-text h1 {
    font-size: 1.5rem;
    text-align: center;
    color: #424242;
    font-family: Poppins;
    line-height: 4rem;
    letter-spacing: 1px;
    text-decoration: underline;
    text-underline-offset: 10px;
    text-decoration-thickness: 1.2px;
    text-decoration-color: #707070f5;
}

.banner-text {
    width: 95%;
    margin: auto
}


@media (max-width: 991px) {
    .banner-text h1 {
        font-size: 1rem;
        text-align: center;
        color: #161616;
        font-family: Poppins;
        line-height: 2rem;
        letter-spacing: 1px;
        text-decoration: underline;
        text-underline-offset: 10px;
        text-decoration-thickness: 1.2px;
        text-decoration-color: #707070f5;
        margin-top: 10px;
    }
}
</style>