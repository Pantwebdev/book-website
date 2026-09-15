<div class="card">
    <div class="card-body">
        <div class="text-center mb-4">
            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center"
                 style="width: 80px; height: 80px;">
                <i class="bi bi-person-fill fs-2"></i>
            </div>
            <h5 class="mt-3 mb-0">{{ auth()->user()->name }}</h5>
            <p class="text-muted">{{ auth()->user()->email }}</p>
        </div>

        <div class="list-group">

          <a href="{{ url('/') }}" class="list-group-item list-group-item-action " data-menu="home" >
                <i class="bi bi-house me-2"></i> Home page                   
            </a>
            <a href="{{ route('customer.dashboard') }}"
               class="list-group-item list-group-item-action {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>

            <a href="{{ route('customer.orders') }}"
               class="list-group-item list-group-item-action {{ request()->routeIs('customer.orders*') ? 'active' : '' }}">
                <i class="bi bi-bag-check me-2"></i> My Orders
            </a>
            <a href="javascript:void(0)"
                class="list-group-item list-group-item-action"
                data-bs-toggle="modal"
                data-bs-target="#trackOrderModal">
                    <i class="bi bi-check me-2"></i> Track Order
            </a>
            <a href="{{ route('customer.cartitem') }}" class="list-group-item list-group-item-action">
                <i class="bi bi-heart me-2"></i> Cart item
            </a>
            <!--<a href="#" class="list-group-item list-group-item-action">-->
            <!--    <i class="bi bi-heart me-2"></i> Wishlist-->
            <!--</a>-->

            <!--<a href="#" class="list-group-item list-group-item-action">-->
            <!--    <i class="bi bi-gear me-2"></i> Account Settings-->
            <!--</a>-->

            <a href="{{ route('customer.logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               class="list-group-item list-group-item-action text-danger">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
            </a>

            <form id="logout-form" action="{{ route('customer.logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>
</div>

<style>
    .list-group-item.active{
            background-color: #cd6902;
    }
</style>

