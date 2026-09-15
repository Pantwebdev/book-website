@include('userheader')

<section class=" arival-main">

    <div class="heading-sectionnew">
        <div class="title">Featured Books</div>

        <div class="dividerswction">
            <img src="{{url('userassets/image/flowericon-img.png')}}" alt="decorative icon">
        </div>
    </div>


    <div class="container-fluid">
       <div id="featuredbooks-list">
        @include('partials.featuredbooks-list')
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

</section>
@include('userfooter')

<script>
$(document).on('click', '.pagination a', function (e) {
    e.preventDefault();
    let page = $(this).attr('href').split('page=')[1];
    fetchnewarrivals(page);
});

function fetchnewarrivals(page) {
    $.ajax({
        url: "/featured?page=" + page,
        success: function (data) {
            $("#featuredbooks-list").html(data);
        }
    });
}

</script>

