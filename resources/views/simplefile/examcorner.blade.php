@include('userheader')

<section class=" arival-main">

    <div class="heading-sectionnew">
        <div class="title">Exam Corner</div>

        <div class="dividerswction">
            <img src="{{url('userassets/image/flowericon-img.png')}}" alt="decorative icon">
        </div>
    </div>


    <div class="container-fluid">
       <div id="examcorner-list">
        @include('partials.examcorner-list')
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
    fetchexamcorner(page);
});

function fetchexamcorner(page) {
    $.ajax({
        url: "/exam-corner?page=" + page,
        success: function (data) {
            $("#examcorner-list").html(data);
        }
    });
}

</script>

