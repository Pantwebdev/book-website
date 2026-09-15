<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="{{ asset('userassets/css/customer-dashboard.css') }}">

    <!-- Bootstrap / Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light pradeep121">

@include('customer.layouts.header')

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3 mb-4">
            @include('customer.layouts.sidebar')
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <div class="bg-white rounded-4 shadow-sm p-4">
                
                
<!-- WELCOME -->
<div class="dashboard-welcome mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h3>👋 Hi {{ auth()->user()->name }}</h3>
        <p class="mb-0">Track your orders & manage your account</p>
    </div>
    <a href="{{ url('/') }}" class="btn btn-light fw-semibold">
        Shop Now
    </a>
</div>

<!-- STATS -->
<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-primary me-3">
                    <i class="bi bi-bag-check"></i>
                </div>
                <div>
                    <h3 class="mb-0">{{ $totalOrders }}</h3>
                    <small class="text-muted">Total Orders</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-success me-3">
                    <i class="bi bi-currency-rupee"></i>
                </div>
                <div>
                    <h3 class="mb-0">₹{{ number_format($totalSpent, 2) }}</h3>
                    <small class="text-muted">Total Spent</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-warning me-3">
                    <i class="bi bi-truck"></i>
                </div>
                <div>
                    <h3 class="mb-0">{{ $deliveredCount }}</h3>
                    <small class="text-muted">In Delivery</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-danger me-3">
                    <i class="bi bi-heart"></i>
                </div>
                <div>
                    <h3 class="mb-0">0</h3>
                    <small class="text-muted">Wishlist</small>
                </div>
            </div>
        </div>
    </div>
</div>
@yield('content')
            </div>
        </div>
    </div>
</div>

@include('customer.layouts.footer')
</body>

</html>
