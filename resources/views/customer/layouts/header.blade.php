<nav class="navbar navbar-expand-lg navbar-dark ">
    <div class="container">
        <!-- <a class="navbar-brand" href="{{ url('/') }}">MyShop</a> -->

        <div class="logoheader">
                        <a href="{{ url('/') }}">
                            @if(!empty($setting->image))
                            <img src="{{ url('userassets/image/' . $setting->image) }}" alt="ajhuie books store">
                            @else
                            <img src="{{ url('userassets/image/logo.png') }}" alt="ajhuie books store">
                            @endif
                        </a>
                    </div>

        <div class="ms-auto text-black">
            Welcome, {{ auth()->user()->name }}
        </div>
    </div>
</nav>


<style>
   .logoheader img{
        height: 55px;
   } 
</style>
