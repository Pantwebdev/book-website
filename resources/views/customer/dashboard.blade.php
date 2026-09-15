@extends('customer.layouts.app')

@section('content')


<!-- RECENT ORDERS -->
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-0">
        <h5 class="mb-0">Recent Orders</h5>
    </div>

    <div class="card-body text-center">
        <div class="empty-state">
            <i class="bi bi-bag-x fs-1 text-muted"></i>
            <h5 class="mt-3">No orders yet</h5>
            <p class="text-muted">Start shopping and your orders will appear here</p>
            <a href="{{ url('/') }}" class="btn btn-primary px-4">
                Start Shopping
            </a>
        </div>
    </div>
</div>

@endsection
