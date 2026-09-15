@include('userheader')
<div class="product_listbanner">
<img src="{{ url('userassets/image/' . $banner->image) }}" alt="">
</div>

<!-- Breadcrumb -->
<div class="breadcrumb">
<p>
    <a href="{{ url('/') }}">Home</a>

    @if(isset($categoryData))
    / <a href="/{{ $categoryData->slug }}">{{ $categoryData->title }}</a>
    @endif

    @if(isset($subcatData))
    / <a href="/{{ $categoryData->slug }}/{{ $subcatData->slug }}">{{ $subcatData->title }}</a>
    @endif

    @if(isset($childData))
    / <a href="/{{ $categoryData->slug }}/{{ $subcatData->slug }}/{{ $childData->slug }}">{{ $childData->title }}</a>
    @endif
</p>
</div>

<!-- Main Container -->
<div class="main-container">
<!-- Sidebar Filters -->
<aside class="sidebar">

   <button class="close-filter" id="closeFilter">
        <i class="bi bi-x-lg"></i>
    </button>
    <h2>Filter By</h2>

    <div class="filter-group">
        <h3>Price</h3>
        <input type="range" min="0" max="10000" step="100" value="10000" id="priceRange" />
        <p>Max Price: ₹<span id="priceValue">10000</span></p>
    </div>

    <div class="filter-group">
<h3>Category</h3>

@foreach($navcategories as $cat)
<label class="filter-label">
<input type="checkbox" class="filter-checkbox" data-filter="category" value="{{ $cat->id }}">
{{ $cat->title }}
</label>
@endforeach

</div>

    <div class="filter-group">
<button id="clearFilters" class="btn-clear" style="display: none;">
    <i class="bi bi-x-circle"></i> Clear All Filters
</button>
</div>
</aside>

<!-- Products Section -->
<section class="products-area">
    <div class="products-header">
        <p id="showingText">
            @if($products->total() > 0)
                Showing {{ $products->firstItem() }}-{{ $products->lastItem() }} of {{ $products->total() }} products
            @else
                No products found
            @endif
        </p>

            <button class="mobile-filter-btn" id="openFilter">
            <i class="bi bi-funnel"></i> Filter
            </button>

        <div class="filter-controls">
            <select id="sortBy">
                <option value="">Sort by</option>
                <option value="lowToHigh">Price: Low to High</option>
                <option value="highToLow">Price: High to Low</option>
                <option value="nameAsc">Name: A to Z</option>
                <option value="nameDesc">Name: Z to A</option>
            </select>
            <select id="perPage">
                <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 per page</option>
                <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20 per page</option>
                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 per page</option>
                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 per page</option>
                <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>All</option>
            </select>
        </div>
    </div>

    <!-- Loading Indicator -->
    <div id="loadingIndicator" style="display: none; text-align: center; padding: 40px;">
        <div class="loading-spinner" style="font-size: 18px; color: #666;">
            <i class="bi bi-arrow-repeat"></i> Loading products...
        </div>
    </div>

    <!-- Products Container -->
    <div class="product-list" id="productContainer">
        @include('partials.product_list', ['products' => $products])
    </div>
</section>
</div>
<style>
    /* ===========================
   MOBILE & TABLET FILTER
=========================== */

/* Filter button hidden on desktop */
.mobile-filter-btn {
    display: none;
    background: #bd832c;
    color: #fff;
    border: none;
    padding: 8px 14px;
    border-radius: 5px;
    font-size: 14px;
    cursor: pointer;
    gap: 6px;
}

/* Close button */
.close-filter {
    display: none;
    background: none;
    border: none;
    font-size: 18px;
    position: absolute;
    top: 15px;
    right: 15px;
    cursor: pointer;
}

/* Tablet & Mobile */
@media (max-width: 991px) {

    .main-container {
        display: block;
    }

    /* Show filter button */
    .mobile-filter-btn {
        display: flex;
        align-items: center;
    }

    /* Sidebar becomes top dropdown */
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #fff;
        z-index: 9999;
        padding: 20px;
        overflow-y: auto;
        transform: translateY(-100%);
        transition: transform 0.3s ease;
    }

    /* Active state */
    .sidebar.active {
        transform: translateY(0);
    }

    .close-filter {
        display: block;
    }

    /* Products full width */
    .products-area {
        width: 100%;
    }

    .products-header {
        gap: 10px;
        flex-wrap: wrap;
    }

    .filter-controls {
        width: 100%;
        justify-content: space-between;
    }
}

</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// Current category data
const categoryData = {
category_id: "{{ $categoryData->id ?? '' }}",
sub_category_id: "{{ $subcatData->id ?? '' }}",
child_sub_category_id: "{{ $childData->id ?? '' }}"
};

// Track current filters state
let currentFilters = {
max_price: 10000,
sort: '',
per_page: '10'
};

// Get all filter values
function getFilterValues() {
const maxPrice = $('#priceRange').val() || '';
const categories = [];

$('input[data-filter="category"]:checked').each(function() {
    categories.push($(this).val());
});

const sort = $('#sortBy').val() || '';
const perPage = $('#perPage').val() || '10';

return {
    max_price: maxPrice,
     categories: categories,
    sort: sort,
    per_page: perPage,
    // Include category data for filtering
    category_id: categoryData.category_id,
    sub_category_id: categoryData.sub_category_id,
    child_sub_category_id: categoryData.child_sub_category_id
};
}

// Apply filters via AJAX
function applyFilters() {
const filters = getFilterValues();
currentFilters = filters; // Update current filters

// Show loading
$('#loadingIndicator').show();
$('#productContainer').hide();

$.ajax({
    url: "{{ route('filter.products') }}",
    type: "POST",
    data: {
        ...filters,
        _token: '{{ csrf_token() }}'
    },
    success: function(response) {
        if (response.status === 'success') {
            $('#productContainer').html(response.html);
            
            // Update showing text with proper counts
            updateShowingText(response);
        } else {
            $('#productContainer').html('<div class="no-products"><p>Error loading products. Please try again.</p></div>');
            $('#showingText').text('Error loading products');
        }
    },
    error: function(xhr) {
        console.error("Filter error:", xhr.responseText);
        $('#productContainer').html('<div class="no-products"><p>Error loading products. Please try again.</p></div>');
        $('#showingText').text('Error loading products');
    },
    complete: function() {
        $('#loadingIndicator').hide();
        $('#productContainer').show();
    }
});
}

// Update showing text based on response
function updateShowingText(response) {
if (response.total > 0) {
    if (response.per_page === 'all' || parseInt(response.per_page) >= response.total) {
        $('#showingText').text(`Showing all ${response.total} products`);
    } else {
        const start = ((response.current_page - 1) * parseInt(response.per_page)) + 1;
        const end = Math.min(response.current_page * parseInt(response.per_page), response.total);
        $('#showingText').text(`Showing ${start}-${end} of ${response.total} products`);
    }
} else {
    $('#showingText').text('No products found');
}
}

// Clear all filters
function clearAllFilters() {
// Reset price range
$('#priceRange').val(10000);
$('#priceValue').text('10000');

// Uncheck all checkboxes
$('.filter-checkbox').prop('checked', false);

// Reset selects
$('#sortBy').val('');
$('#perPage').val('10');

// Reset current filters
currentFilters = {
    max_price: 10000,
    sort: '',
    per_page: '10'
};

// Apply filters (will show all products)
applyFilters();
}

// Check if any filter is active
function hasActiveFilters() {
const filters = getFilterValues();

return filters.max_price !== '10000' || 
         filters.categories.length > 0 || 
        filters.sort !== '' || 
        filters.per_page !== '10';
}

// Update showing text on page load
function updateInitialShowingText() {
const total = {{ $products->total() }};
const currentPage = {{ $products->currentPage() }};
const perPage = {{ $products->perPage() }};

if (total > 0) {
    if (perPage >= total) {
        $('#showingText').text(`Showing all ${total} products`);
    } else {
        const start = ((currentPage - 1) * perPage) + 1;
        const end = Math.min(currentPage * perPage, total);
        $('#showingText').text(`Showing ${start}-${end} of ${total} products`);
    }
} else {
    $('#showingText').text('No products found');
}
}

// Reset to show all products (when no filters are active)
function showAllProducts() {
const filters = {
    max_price: 10000,
   
    sort: $('#sortBy').val() || '',
    per_page: $('#perPage').val() || '10',
    category_id: categoryData.category_id,
    sub_category_id: categoryData.sub_category_id,
    child_sub_category_id: categoryData.child_sub_category_id
};

// Show loading
$('#loadingIndicator').show();
$('#productContainer').hide();

$.ajax({
    url: "{{ route('filter.products') }}",
    type: "POST",
    data: {
        ...filters,
        _token: '{{ csrf_token() }}'
    },
    success: function(response) {
        if (response.status === 'success') {
            $('#productContainer').html(response.html);
            updateShowingText(response);
        }
    },
    error: function(xhr) {
        console.error("Error:", xhr.responseText);
    },
    complete: function() {
        $('#loadingIndicator').hide();
        $('#productContainer').show();
    }
});
}

// Event handlers
$(document).ready(function() {
// Update initial showing text
updateInitialShowingText();

// Initialize current filters
currentFilters = getFilterValues();

// Price range
$('#priceRange').on('input', function() {
    $('#priceValue').text(this.value);
});

// Apply filters on change
$('#priceRange').on('change', function() {
    applyFilters();
});

// Checkboxes - apply immediately
$('.filter-checkbox').on('change', function() {
    applyFilters();
});

// Selects - apply immediately
$('#sortBy').on('change', function() {
    // If no other filters are active, show all products with sorting
    if (!hasActiveFilters()) {
        showAllProducts();
    } else {
        applyFilters();
    }
});

$('#perPage').on('change', function() {
    // If no other filters are active, show all products with pagination
    if (!hasActiveFilters()) {
        showAllProducts();
    } else {
        applyFilters();
    }
});

// Clear filters button
$('#clearFilters').on('click', clearAllFilters);

// Show/hide clear filters button based on active filters
function updateClearButton() {
    if (hasActiveFilters()) {
        $('#clearFilters').show();
    } else {
        $('#clearFilters').hide();
    }
}

// Update clear button on filter changes
$(document).on('change', '.filter-checkbox, #sortBy, #perPage, #priceRange', updateClearButton);

// Initial update of clear button
updateClearButton();

// Pagination links (delegated event)
$(document).on('click', '.pagination a', function(e) {
    e.preventDefault();
    
    const page = $(this).attr('href').split('page=')[1];
    
    $('#loadingIndicator').show();
    $('#productContainer').hide();
    
    const filters = getFilterValues();
    filters.page = page;
    
    $.ajax({
        url: "{{ route('filter.products') }}",
        type: "POST",
        data: {
            ...filters,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.status === 'success') {
                $('#productContainer').html(response.html);
                updateShowingText(response);
            }
        },
        error: function(xhr) {
            console.error("Pagination error:", xhr.responseText);
            $('#productContainer').html('<div class="no-products"><p>Error loading products.</p></div>');
        },
        complete: function() {
            $('#loadingIndicator').hide();
            $('#productContainer').show();
            
            // Scroll to top of products
            $('html, body').animate({
                scrollTop: $('#productContainer').offset().top - 100
            }, 500);
        }
    });
});
});

// Add to cart functionality
// Add to cart functionality
$(document).on('submit', '.addToCartForm', function(e) {
e.preventDefault();
const form = $(this);
const submitBtn = form.find('.add-to-cart');
const originalText = submitBtn.find('span').text();
const originalHtml = submitBtn.html();

// Show loading state
submitBtn.prop('disabled', true);
submitBtn.html('<i class="bi bi-arrow-repeat spinner"></i> Adding...');

$.ajax({
    url: form.attr('action'),
    type: "POST",
    data: form.serialize(),
    success: function(response) {
        if (response.success) {
            // Show success message
            showToast(response.message, 'success');
            
            // Update cart count in header if exists
            if (response.cart_count) {
                updateCartCount(response.cart_count);
            }
        } else {
            showToast(response.message || 'Error adding product to cart', 'error');
        }
    },
    error: function(xhr) {
        console.error("Add to cart error:", xhr.responseText);
        let errorMessage = 'Error adding product to cart';
        
        if (xhr.responseJSON && xhr.responseJSON.message) {
            errorMessage = xhr.responseJSON.message;
        }
        
        showToast(errorMessage, 'error');
    },
    complete: function() {
        // Restore button state
        submitBtn.prop('disabled', false);
        submitBtn.html(originalHtml);
    }
});
});

// Update cart count in header
function updateCartCount(count) {
// Update cart count in header if the element exists
const cartCountElement = $('.cart-count, #cart-count, .cart-count-badge');
if (cartCountElement.length > 0) {
    cartCountElement.text(count);
    cartCountElement.show();
} else {
    // If cart count element doesn't exist, create one
    $('body').append(`<div class="cart-count-global" style="display:none">${count}</div>`);
}
}

// Toast notification function
function showToast(message, type = 'info') {
// Remove existing toasts
$('.custom-toast').remove();

const icons = {
    'success': 'bi-check-circle',
    'error': 'bi-x-circle',
    'info': 'bi-info-circle'
};

const toast = $(`
    <div class="custom-toast custom-toast-${type}">
        <div class="toast-content">
            <i class="bi ${icons[type]}"></i>
            <span>${message}</span>
        </div>
        <button class="toast-close">&times;</button>
    </div>
`);

$('body').append(toast);

// Show toast with animation
setTimeout(() => {
    toast.addClass('show');
}, 100);

// Auto remove after 5 seconds
setTimeout(() => {
    toast.removeClass('show');
    setTimeout(() => toast.remove(), 300);
}, 800);

// Close on click
toast.find('.toast-close').on('click', function() {
    toast.removeClass('show');
    setTimeout(() => toast.remove(), 300);
});
}

// Add CSS for toast notifications
if (!$('#toast-styles').length) {
$('head').append(`
    <style id="toast-styles">
        .custom-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            border-radius: 8px;
            padding: 15px 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border-left: 4px solid #007bff;
            z-index: 9999;
            transform: translateX(400px);
            transition: transform 0.3s ease;
            max-width: 350px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .custom-toast.show {
            transform: translateX(0);
        }
        
        .custom-toast-success {
            border-left-color: #28a745;
        }
        
        .custom-toast-error {
            border-left-color: #dc3545;
        }
        
        .custom-toast-info {
            border-left-color: #17a2b8;
        }
        
        .toast-content {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
        }
        
        .toast-content i {
            font-size: 20px;
        }
        
        .custom-toast-success .toast-content i {
            color: #28a745;
        }
        
        .custom-toast-error .toast-content i {
            color: #dc3545;
        }
        
        .custom-toast-info .toast-content i {
            color: #17a2b8;
        }
        
        .toast-close {
            background: none;
            border: none;
            font-size: 18px;
            cursor: pointer;
            color: #666;
            margin-left: 10px;
        }
        
        .spinner {
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
`);
}
</script>

<!-- moblie fillter ke liye js  -->

<script>
$(document).ready(function () {

    $('#openFilter').on('click', function () {
        $('.sidebar').addClass('active');
        $('body').css('overflow', 'hidden');
    });

    $('#closeFilter').on('click', function () {
        $('.sidebar').removeClass('active');
        $('body').css('overflow', 'auto');
    });

});
</script>

<style>
.filter-label {
display: flex;
align-items: center;
margin-bottom: 8px;
cursor: pointer;
padding: 5px 0;
}

.filter-checkbox {
margin-right: 10px;
}

.checkmark {
width: 18px;
height: 18px;
border: 2px solid #ddd;
border-radius: 3px;
margin-right: 10px;
display: inline-block;
position: relative;
}

.filter-checkbox:checked + .checkmark {
background-color: #007bff;
border-color: #007bff;
}

.filter-checkbox:checked + .checkmark:after {
content: '✓';
color: white;
position: absolute;
top: -2px;
left: 2px;
font-size: 12px;
}

.products-header {
display: flex;
justify-content: space-between;
align-items: center;
margin-bottom: 20px;
padding: 15px 0;
border-bottom: 1px solid #eee;
}

.filter-controls {
display: flex;
gap: 15px;
}

.filter-controls select {
padding: 8px 15px;
border: 1px solid #ddd;
border-radius: 5px;
background: white;
}





.btn-clear {
background: #6c757d;
color: white;
border: none;
padding: 10px 20px;
border-radius: 5px;
cursor: pointer;
margin-top: 15px;
width: 100%;
}

.btn-clear:hover {
background: #5a6268;
}

.loading-spinner {
animation: spin 1s linear infinite;
}

@keyframes spin {
0% { transform: rotate(0deg); }
100% { transform: rotate(360deg); }
}

.no-products {
text-align: center;
padding: 40px;
color: #666;
}

.no-products p {
font-size: 18px;
margin-bottom: 15px;
}
.btn-clear {
background: #dc3545;
color: white;
border: none;
padding: 10px 15px;
border-radius: 5px;
cursor: pointer;
margin-top: 15px;
width: 100%;
font-size: 14px;
transition: background 0.3s;
}

.btn-clear:hover {
background: #c82333;
}

.btn-clear i {
margin-right: 5px;
}
</style>

@include('userfooter')