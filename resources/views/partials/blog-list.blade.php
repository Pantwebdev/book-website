<div class="blogcard">
    @foreach($blogdata as $blog)
    <div class="card-blog">
        <img src="{{ url('userassets/image/blog/'.$blog->image) }}" alt="{{ $blog->title }}">

        <h3 class="blog-title">{{ $blog->title }}</h3>
        <p class="blog-content">
            {{ Str::limit(strip_tags($blog->Short_content), 12) }}
        </p>
        <p class="blog-content">
            {{ Str::limit(strip_tags($blog->content), 35) }}
        </p>

        <a href="{{ url('blog/'.$blog->slug) }}" class="read-btn">Read More</a>
    </div>
    @endforeach
</div>

<div class="pagination-wrapper">
    {!! $blogdata->links('pagination::bootstrap-5') !!}
</div>

<style>
.blog-content {
    margin-bottom: 0px;

}

.card-blog {
    margin-bottom: 30px;
}
</style>