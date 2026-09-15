<!-- Filter Form -->
<div class="card" id="filterCard">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-filter mr-2"></i>Filters
            </h5>
            <button type="button" class="btn btn-sm btn-link" data-toggle="collapse" data-target="#filterCollapse">
                <i class="fas fa-chevron-down"></i>
            </button>
        </div>
    </div>
    
    <div class="collapse show" id="filterCollapse">
        <div class="card-body">
            <form id="filterForm">
                @csrf
                <div class="row">
                    <!-- Basic Filters -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="order_number">Order Number</label>
                            <input type="text" class="form-control" id="order_number" 
                                   placeholder="Search by order number...">
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="customer_name">Customer Name/Email/Phone</label>
                            <input type="text" class="form-control" id="customer_name" 
                                   placeholder="Customer name, email or phone...">
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="product_search">Product Name/SKU</label>
                            <input type="text" class="form-control" id="product_search" 
                                   placeholder="Product name or SKU...">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <!-- Status Filters -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="order_status">Order Status</label>
                            <select class="form-control select2" id="order_status">
                                <option value="all">All Statuses</option>
                                @foreach($orderStatuses as $status)
                                    <option value="{{ $status }}">{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="payment_status">Payment Status</label>
                            <select class="form-control select2" id="payment_status">
                                <option value="all">All Payment Statuses</option>
                                @foreach($paymentStatuses as $status)
                                    <option value="{{ $status }}">{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="payment_method">Payment Method</label>
                            <select class="form-control select2" id="payment_method">
                                <option value="all">All Methods</option>
                                @foreach($paymentMethods as $method)
                                    <option value="{{ $method }}">{{ strtoupper($method) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="order_source">Order Source</label>
                            <select class="form-control select2" id="order_source">
                                <option value="all">All Sources</option>
                                <option value="website">Website</option>
                                <option value="mobile_app">Mobile App</option>
                                <option value="pos">POS</option>
                                <option value="marketplace">Marketplace</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <!-- Date Range -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="date_from">Date From</label>
                            <input type="date" class="form-control" id="date_from">
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="date_to">Date To</label>
                            <input type="date" class="form-control" id="date_to">
                        </div>
                    </div>
                    
                    <!-- Amount Range -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="min_amount">Min Amount (₹)</label>
                            <input type="number" class="form-control" id="min_amount" 
                                   placeholder="0" min="0" step="0.01">
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="max_amount">Max Amount (₹)</label>
                            <input type="number" class="form-control" id="max_amount" 
                                   placeholder="100000" min="0" step="0.01">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <!-- Location Filters -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="city">City</label>
                            <select class="form-control select2" id="city">
                                <option value="">All Cities</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city }}">{{ $city }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="state">State</label>
                            <select class="form-control select2" id="state">
                                <option value="">All States</option>
                                @foreach($states as $state)
                                    <option value="{{ $state }}">{{ $state }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="pincode">Pincode</label>
                            <input type="text" class="form-control" id="pincode" 
                                   placeholder="Enter pincode...">
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="country">Country</label>
                            <select class="form-control select2" id="country">
                                <option value="">All Countries</option>
                                <option value="India" selected>India</option>
                                <option value="USA">USA</option>
                                <option value="UK">UK</option>
                                <option value="Canada">Canada</option>
                                <option value="Australia">Australia</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <!-- Advanced Filters -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="shipping_method">Shipping Method</label>
                            <select class="form-control select2" id="shipping_method">
                                <option value="all">All Methods</option>
                                <option value="standard">Standard</option>
                                <option value="express">Express</option>
                                <option value="overnight">Overnight</option>
                                <option value="free_shipping">Free Shipping</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="coupon_code">Coupon Code</label>
                            <input type="text" class="form-control" id="coupon_code" 
                                   placeholder="Coupon code...">
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="item_count">Min Items in Order</label>
                            <input type="number" class="form-control" id="item_count" 
                                   placeholder="0" min="0">
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sort">Sort By</label>
                            <select class="form-control select2" id="sort">
                                <option value="newest">Newest First</option>
                                <option value="oldest">Oldest First</option>
                                <option value="total_high">Total: High to Low</option>
                                <option value="total_low">Total: Low to High</option>
                                <option value="a-z">Customer Name: A-Z</option>
                                <option value="z-a">Customer Name: Z-A</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Date Filters -->
                <div class="row">
                    <div class="col-12">
                        <label class="mb-2">Quick Date Filters:</label>
                        <div class="btn-group btn-group-sm mb-3" role="group">
                            <button type="button" class="btn btn-outline-secondary date-quick" data-days="1">Today</button>
                            <button type="button" class="btn btn-outline-secondary date-quick" data-days="7">Last 7 Days</button>
                            <button type="button" class="btn btn-outline-secondary date-quick" data-days="30">Last 30 Days</button>
                            <button type="button" class="btn btn-outline-secondary date-quick" data-days="90">Last 90 Days</button>
                            <button type="button" class="btn btn-outline-secondary" id="thisMonth">This Month</button>
                            <button type="button" class="btn btn-outline-secondary" id="lastMonth">Last Month</button>
                        </div>
                    </div>
                </div>
                
                <!-- Active Filters Display -->
                <div class="row" id="activeFilters" style="display: none;">
                    <div class="col-12">
                        <div class="alert alert-info py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Active Filters:</strong>
                                    <div id="filterBadges" class="d-inline ml-2"></div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-info" id="clearAllFilters">
                                    <i class="fas fa-times"></i> Clear All
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="row">
                    <div class="col-12 text-right">
                        <button type="button" class="btn btn-primary" id="applyFilters">
                            <i class="fas fa-search mr-1"></i> Apply Filters
                        </button>
                        <button type="button" class="btn btn-secondary" id="resetFilters">
                            <i class="fas fa-redo mr-1"></i> Reset
                        </button>
                        <button type="button" class="btn btn-success" id="exportBtn">
                            <i class="fas fa-file-export mr-1"></i> Export
                        </button>
                        <button type="button" class="btn btn-info" id="saveFilterBtn">
                            <i class="fas fa-save mr-1"></i> Save Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>