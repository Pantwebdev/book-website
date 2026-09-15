@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">
                <i class="fas fa-shopping-cart"></i>
                Orders Management
            </h4>
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="{{ route('dashboard') }}">
                        <i class="flaticon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="flaticon-right-arrow"></i>
                </li>
                <li class="nav-item">
                    <span>Orders</span>
                </li>
            </ul>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-primary bubble-shadow-small">
                                    <i class="fas fa-shopping-bag"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Total Orders</p>
                                    <h4 class="card-title" id="totalOrders">{{ $totalOrders }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-success bubble-shadow-small">
                           <i class="bi bi-calendar2-event"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Today's Orders</p>
                                    <h4 class="card-title" id="todayOrders">{{ $todayOrders }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-info bubble-shadow-small">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Total Revenue</p>
                                    <h4 class="card-title" id="totalRevenue">₹{{ number_format($totalRevenue, 2) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-warning bubble-shadow-small">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Pending Orders</p>
                                    <h4 class="card-title" id="pendingOrders">{{ $pendingOrders }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Card -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">
                        <i class="fas fa-filter"></i> Filters
                    </h4>
                    <!-- <div class="ml-auto">
                        <button type="button" class="btn btn-sm btn-light" id="resetFilters">
                            <i class="fas fa-redo"></i> Reset
                        </button>
                    </div> -->
                </div>
            </div>
            <div class="card-body">
                <form id="filterForm">
                    <div class="row">
                        <!-- Order ID -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="order_number">Order ID</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="order_number" 
                                       name="order_number" 
                                       placeholder="Search by order number"
                                       value="{{ request('order_number') }}">
                            </div>
                        </div>

                        <!-- Customer Search -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="customer">Customer</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="customer" 
                                       name="customer" 
                                       placeholder="Name, Mobile, Email"
                                       value="{{ request('customer') }}">
                            </div>
                        </div>

                        <!-- Order Status -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="order_status">Order Status</label>
                                <select class="form-control select2" id="order_status" name="order_status">
                                    <option value="">All Status</option>
                                    @php
                                        $statuses = App\Models\OrderStatus::where('status', 1)->get();
                                    @endphp
                                    @foreach($statuses as $status)
                                        <option value="{{ $status->order_status }}" 
                                            {{ request('order_status') == $status->order_status ? 'selected' : '' }}>
                                            {{ $status->order_status }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Payment Status -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="payment_status">Payment Status</label>
                                <select class="form-control select2" id="payment_status" name="payment_status">
                                    <option value="">All Payment Status</option>
                                    @php
                                        $paymentStatuses = App\Models\PaymentStatus::all();
                                    @endphp
                                    @foreach($paymentStatuses as $status)
                                        <option value="{{ $status->payment_status }}" 
                                            {{ request('payment_status') == $status->payment_status ? 'selected' : '' }}>
                                            {{ $status->payment_status }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Payment Method -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="payment_method">Payment Method</label>
                                <select class="form-control select2" id="payment_method" name="payment_method">
                                    <option value="">All Methods</option>
                                    @php
                                        $paymentMethods = App\Models\PaymentMethod::all();
                                    @endphp
                                    @foreach($paymentMethods as $method)
                                        <option value="{{ $method->payment_type }}" 
                                            {{ request('payment_method') == $method->payment_type ? 'selected' : '' }}>
                                            {{ $method->payment_type }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Delivery Status -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="delivery_status">Delivery Status</label>
                                <select class="form-control select2" id="delivery_status" name="delivery_status">
                                    <option value="">All Delivery Status</option>
                                    <option value="shipped" {{ request('delivery_status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                    <option value="delivered" {{ request('delivery_status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="out_for_delivery" {{ request('delivery_status') == 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                                </select>
                            </div>
                        </div>

                        <!-- Date Range -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date_from">From Date</label>
                                <input type="date" 
                                       class="form-control" 
                                       id="date_from" 
                                       name="date_from"
                                       value="{{ request('date_from') }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date_to">To Date</label>
                                <input type="date" 
                                       class="form-control" 
                                       id="date_to" 
                                       name="date_to"
                                       value="{{ request('date_to') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Order Amount Range -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="min_amount">Min Amount (₹)</label>
                                <input type="number" 
                                       class="form-control" 
                                       id="min_amount" 
                                       name="min_amount" 
                                       placeholder="Min amount"
                                       min="0"
                                       step="0.01"
                                       value="{{ request('min_amount') }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="max_amount">Max Amount (₹)</label>
                                <input type="number" 
                                       class="form-control" 
                                       id="max_amount" 
                                       name="max_amount" 
                                       placeholder="Max amount"
                                       min="0"
                                       step="0.01"
                                       value="{{ request('max_amount') }}">
                            </div>
                        </div>

                        <!-- Sort By -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="sort">Sort By</label>
                                <select class="form-control select2" id="sort" name="sort">
                                    <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                                    <option value="total_high" {{ request('sort') == 'total_high' ? 'selected' : '' }}>Total: High to Low</option>
                                    <option value="total_low" {{ request('sort') == 'total_low' ? 'selected' : '' }}>Total: Low to High</option>
                                    <option value="a-z" {{ request('sort') == 'a-z' ? 'selected' : '' }}>Customer A-Z</option>
                                    <option value="z-a" {{ request('sort') == 'z-a' ? 'selected' : '' }}>Customer Z-A</option>
                                </select>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-md-3">
                            <div class="form-group" style="margin-top: 30px;">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-search"></i> Apply Filters
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Active Filters Badges -->
                <div id="activeFilters" class="mt-3 d-none">
                    <strong>Active Filters:</strong>
                    <div id="filterBadges" class="d-inline-block ml-2"></div>
                </div>
            </div>
        </div>

        <!-- Orders Table Card -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">All Orders</h4>
                    <div class="ml-auto">
                        <!-- <button type="button" class="btn btn-success" id="exportBtn">
                            <i class="fas fa-file-export"></i> Export to CSV
                        </button> -->
                        <div class="text-muted ml-3" style="display: inline-block;" id="orderStats">
                            Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} entries
                            @if($orders->lastPage() > 1)
                             | Page {{ $orders->currentPage() }} of {{ $orders->lastPage() }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive" id="ordersTableContainer">
                    @include('admin.orders.partials.orders_table', ['orders' => $orders])
                </div>
                
                <!-- Pagination -->
                @if($orders->lastPage() > 1)
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div id="paginationContainer">
                        {{ $orders->links('pagination::bootstrap-4') }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete order <strong id="deleteOrderNumber"></strong>? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Order</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
.select2-container--default .select2-selection--single {
    height: 45px;
    padding: 8px;
    border: 1px solid #e3e3e3;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 28px;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 43px;
}

.filter-badge {
    background-color: #e9ecef;
    color: #495057;
    padding: 5px 10px;
    border-radius: 15px;
    margin-right: 5px;
    margin-bottom: 5px;
    display: inline-block;
    font-size: 12px;
}

.filter-badge .remove-filter {
    cursor: pointer;
    margin-left: 5px;
    color: #dc3545;
}

.filter-badge .remove-filter:hover {
    color: #a71d2a;
}

#activeFilters {
    border-top: 1px solid #dee2e6;
    padding-top: 15px;
}

.loading-overlay {
    display: none;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.8);
    z-index: 1000;
    justify-content: center;
    align-items: center;
}

.loading-overlay.active {
    display: flex;
}
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        placeholder: "Select...",
        allowClear: true
    });

    // Initialize delete buttons
    $(document).on('click', '.delete-order', function() {
        const orderId = $(this).data('id');
        const orderNumber = $(this).data('number');
        
        $('#deleteOrderNumber').text(orderNumber);
        $('#deleteForm').attr('action', '/admin/orders/' + orderId);
        $('#deleteModal').modal('show');
    });

    // Filter form submission (AJAX)
    $('#filterForm').on('submit', function(e) {
        e.preventDefault();
        applyFilters();
    });

    // Export button click
    $('#exportBtn').on('click', function() {
        exportOrders();
    });

    // Reset filters - HARD RESET with page reload
    $('#resetFilters').on('click', function() {
        // Clear session and reload without parameters
        $.ajax({
            url: '{{ route('admin.orders.index') }}',
            method: 'GET',
            data: { '_reset': true, '_token': '{{ csrf_token() }}' },
            beforeSend: function() {
                window.location.href = '{{ route('admin.orders.index') }}';
            }
        });
    });

    // Auto-apply filters on certain input changes
    $('select.select2').on('change', function() {
        applyFilters();
    });

    // Date inputs change
    $('#date_from, #date_to').on('change', function() {
        applyFilters();
    });

    // Amount inputs change with debounce
    let amountTimer;
    $('#min_amount, #max_amount').on('keyup', function() {
        clearTimeout(amountTimer);
        amountTimer = setTimeout(() => {
            applyFilters();
        }, 800);
    });

    // Debounced search for text inputs
    let searchTimer;
    $('#order_number, #customer').on('keyup', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            applyFilters();
        }, 500);
    });

    // Apply filters function
    function applyFilters() {
        const formData = $('#filterForm').serialize();
        
        // Show loading
        showLoading();
        
        $.ajax({
            url: '{{ route('admin.orders.index') }}',
            method: 'GET',
            data: formData,
            success: function(response) {
                if (typeof response === 'object') {
                    // AJAX response with JSON
                    $('#ordersTableContainer').html(response.table);
                    
                    // Update statistics cards
                    $('.page-inner .row:first').html(response.stats);
                    
                    // Update order stats
                    $('#orderStats').html(response.orderStats);
                } else {
                    // Full page response (shouldn't happen with AJAX)
                    $('#ordersTableContainer').html($(response).find('#ordersTableContainer').html());
                }
                
                // Update pagination
                if ($('#paginationContainer').length) {
                    if (typeof response === 'object') {
                        // We need to update pagination from the table response
                        // This is handled in the table partial
                    } else {
                        $('#paginationContainer').html($(response).find('#paginationContainer').html());
                    }
                }
                
                // Update active filters badges
                updateActiveFilters(formData);
                
                // Update URL without reloading
                updateUrl(formData);
                
                // Hide loading
                hideLoading();
            },
            error: function(xhr) {
                console.error('Filter error:', xhr);
                hideLoading();
                toastr.error('Error applying filters');
            }
        });
    }

    // Export function
    function exportOrders() {
        const formData = $('#filterForm').serialize();
        const exportUrl = '{{ route('admin.orders.export') }}?' + formData;
        window.open(exportUrl, '_blank');
    }

    // Update URL with filters
    function updateUrl(formData) {
        const url = new URL(window.location);
        const params = new URLSearchParams(formData);
        
        // Clear existing params
        url.search = '';
        
        // Add new params
        url.search = params.toString();
        
        // Update browser URL without reload
        history.pushState({}, '', url.toString());
    }

    // Show loading overlay
    function showLoading() {
        $('#ordersTableContainer').append(`
            <div class="loading-overlay active">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        `);
    }

    // Hide loading overlay
    function hideLoading() {
        $('.loading-overlay').remove();
    }

    // Update active filters badges
    function updateActiveFilters(formData) {
        const params = new URLSearchParams(formData);
        const badges = [];
        
        // Helper function to add badge
        function addBadge(key, value, label) {
            if (value) {
                badges.push(`
                    <span class="filter-badge">
                        ${label}: ${value}
                        <span class="remove-filter" data-key="${key}">
                            <i class="fas fa-times"></i>
                        </span>
                    </span>
                `);
            }
        }
        
        // Check each parameter
        addBadge('order_number', params.get('order_number'), 'Order ID');
        addBadge('customer', params.get('customer'), 'Customer');
        addBadge('order_status', params.get('order_status'), 'Order Status');
        addBadge('payment_status', params.get('payment_status'), 'Payment Status');
        addBadge('payment_method', params.get('payment_method'), 'Payment Method');
        addBadge('delivery_status', params.get('delivery_status'), 'Delivery Status');
        
        const dateFrom = params.get('date_from');
        const dateTo = params.get('date_to');
        if (dateFrom || dateTo) {
            const dateRange = `${dateFrom || 'Start'} to ${dateTo || 'End'}`;
            badges.push(`
                <span class="filter-badge">
                    Date Range: ${dateRange}
                    <span class="remove-filter" data-key="date">
                        <i class="fas fa-times"></i>
                    </span>
                </span>
            `);
        }
        
        const minAmount = params.get('min_amount');
        const maxAmount = params.get('max_amount');
        if (minAmount || maxAmount) {
            const amountRange = `${minAmount ? '₹' + minAmount : 'Min'} to ${maxAmount ? '₹' + maxAmount : 'Max'}`;
            badges.push(`
                <span class="filter-badge">
                    Amount: ${amountRange}
                    <span class="remove-filter" data-key="amount">
                        <i class="fas fa-times"></i>
                    </span>
                </span>
            `);
        }
        
        const sort = params.get('sort');
        if (sort && sort !== 'newest') {
            const sortLabels = {
                'oldest': 'Oldest First',
                'total_high': 'Total: High to Low',
                'total_low': 'Total: Low to High',
                'a-z': 'Customer A-Z',
                'z-a': 'Customer Z-A'
            };
            addBadge('sort', sortLabels[sort] || sort, 'Sort By');
        }
        
        // Update badges container
        if (badges.length > 0) {
            $('#filterBadges').html(badges.join(''));
            $('#activeFilters').removeClass('d-none');
        } else {
            $('#activeFilters').addClass('d-none');
        }
        
        // Add click handlers for remove buttons
        $('.remove-filter').on('click', function() {
            const key = $(this).data('key');
            removeFilter(key);
        });
    }

    // Remove specific filter
    function removeFilter(key) {
        switch(key) {
            case 'date':
                $('#date_from').val('');
                $('#date_to').val('');
                break;
            case 'amount':
                $('#min_amount').val('');
                $('#max_amount').val('');
                break;
            default:
                $(`#${key}`).val('').trigger('change');
                break;
        }
        applyFilters();
    }

    // Handle pagination clicks (AJAX)
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        const pageUrl = $(this).attr('href');
        const page = new URL(pageUrl).searchParams.get('page');
        
        // Get current filters
        const formData = $('#filterForm').serialize();
        
        // Create new URL with filters and page
        const params = new URLSearchParams(formData);
        params.set('page', page);
        
        showLoading();
        
        $.ajax({
            url: '{{ route('admin.orders.index') }}',
            method: 'GET',
            data: params.toString(),
            success: function(response) {
                if (typeof response === 'object') {
                    $('#ordersTableContainer').html(response.table);
                    
                    // Update pagination container
                    const paginationHtml = $(response.table).closest('#ordersTableContainer').next('#paginationContainer').html();
                    if (paginationHtml) {
                        $('#paginationContainer').html(paginationHtml);
                    }
                }
                hideLoading();
            },
            error: function(xhr) {
                console.error('Pagination error:', xhr);
                hideLoading();
            }
        });
    });

    // Handle browser refresh - clear filters
    function handlePageRefresh() {
        // Check if page was reloaded
        const performance = window.performance || window.mozPerformance || window.msPerformance || window.webkitPerformance;
        
        if (performance && performance.navigation) {
            if (performance.navigation.type === 1) { // TYPE_RELOAD
                // Page was reloaded
                if (window.location.search) {
                    // Show message that filters were cleared
                    toastr.info('Filters cleared due to page refresh');
                    
                    // Reset form
                    $('#filterForm')[0].reset();
                    $('.select2').val(null).trigger('change');
                    
                    // Clear active filters display
                    $('#activeFilters').addClass('d-none');
                    $('#filterBadges').html('');
                    
                    // Clear URL
                    history.replaceState({}, document.title, window.location.pathname);
                    
                    // Apply filters (empty) to refresh data
                    setTimeout(() => {
                        applyFilters();
                    }, 100);
                }
            }
        }
    }

    // Initialize active filters on page load
    const initialFormData = $('#filterForm').serialize();
    updateActiveFilters(initialFormData);
    
    // Handle page refresh
    handlePageRefresh();
});
</script>
@endsection