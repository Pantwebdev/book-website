@include('userheader')

<section class=" arival-main">

    <div class="heading-sectionnew">
        <div class="title">New Arrivals</div>

        <div class="dividerswction">
            <img src="{{url('userassets/image/flowericon-img.png')}}" alt="decorative icon">
        </div>
    </div>


    <div class="container-fluid">
       <div id="newarrivals-list">
        @include('partials.newarrivals-list')
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
        url: "/new-arrivals?page=" + page,
        success: function (data) {
            $("#newarrivals-list").html(data);
        }
    });
}

</script>

