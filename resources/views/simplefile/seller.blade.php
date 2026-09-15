@include('userheader')

<section class=" arival-main">

    <div class="heading-sectionnew">
        <div class="title">Best Seller</div>

        <div class="dividerswction">
            <img src="{{url('userassets/image/flowericon-img.png')}}" alt="decorative icon">
        </div>
    </div>


     <div class="container-fluid">
       <div id="seller-list">
        @include('partials.seller-list')
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
    fetchseller(page);
});

function fetchseller(page) {
    $.ajax({
        url: "/seller?page=" + page,
        success: function (data) {
            $("#seller-list").html(data);
        }
    });
}

</script>