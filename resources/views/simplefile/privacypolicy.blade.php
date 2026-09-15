@include('userheader')

<div class="product_listbanner">
    <img src="{{ url('userassets/image/' . $banner->image) }}" alt="Privacy Policy">
</div>

<section class="help-support-section">
    <div class="container">
        <h2 class="page-title">{{$privacypolicy->title}}</h2>
        <p class="page-subtitle">
            {{$privacypolicy->short_content}}
        </p>
        <p class="page-subtitle">
            {!!$privacypolicy->content!!}
        </p>
          
    </div>
</section>
@include('userfooter')
<style>
.help-support-section{
    padding: 40px 0;
    background: #fafafa;
}

.page-title{
    text-align: center;
    font-size: 32px;
    font-weight: 700;
}

.page-subtitle{
    text-align: center;
    color: #666;
    margin-bottom: 40px;
}

.support-grid{
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.support-card{
    background: #fff;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    text-align: center;
}

.support-card i{
    font-size: 35px;
    color: #007185;
    margin-bottom: 15px;
}

.support-card h4{
    font-size: 18px;
    margin-bottom: 10px;
}

.support-card ul{
    list-style: none;
    padding: 0;
    margin: 0;
}

.support-card ul li{
    font-size: 14px;
    color: #555;
    margin-bottom: 6px;
}

.contact-support{
    margin-top: 50px;
    background: #fff;
    padding: 30px;
    border-radius: 10px;
    text-align: center;
}

.contact-support h3{
    font-size: 22px;
    margin-bottom: 10px;
}

.contact-details p{
    font-size: 15px;
    margin: 5px 0;
}
</style>
