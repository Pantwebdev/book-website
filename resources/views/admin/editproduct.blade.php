@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="page-inner">
       

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif


                    <h3 class="fw-bold mb-3">Edit Product</h3>

                </div>

                <form action="{{ route('product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf




                    <div class="card-body">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item"><button class="nav-link active" id="categories-tab"
                                    data-bs-toggle="tab" data-bs-target="#categories" type="button">Categories</button>
                            </li>
                            <li class="nav-item"><button class="nav-link" id="general-tab" data-bs-toggle="tab"
                                    data-bs-target="#general" type="button">General</button></li>
                            <li class="nav-item"><button class="nav-link" id="price-tab" data-bs-toggle="tab"
                                    data-bs-target="#price" type="button">Price</button></li>
                            <li class="nav-item"><button class="nav-link" id="images-tab" data-bs-toggle="tab"
                                    data-bs-target="#images" type="button">Images</button></li>
                            
                        </ul>

                        <div class="tab-content mt-3" id="myTabContent">
                          
                            <!-- ✅ Categories Tab -->
                            <div class="tab-pane fade show active" id="categories" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6 col-lg-4">
                                        <label>Select Category</label>
                                        <select class="form-select" name="category_id" id="category_id">
                                            <option value="">Select Category</option>
                                            @foreach($categories as $cg)
                                            <option value="{{ $cg->id }}"
                                                {{ $product->category_id == $cg->id ? 'selected' : '' }}>
                                                {{ $cg->title }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-md-6 col-lg-4">
                                        <label>Select Subcategory</label>
                                        <select class="form-select" name="sub_category_id" id="subcategory_id">
                                            <option value="">Select Subcategory</option>
                                            @foreach($subcategories as $sub)
                                            <option value="{{ $sub->id }}"
                                                {{ $product->sub_category_id == $sub->id ? 'selected' : '' }}>
                                                {{ $sub->title }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-md-6 col-lg-4">
                                        <label>Select Child SubCategory</label>
                                        <select class="form-select" name="child_sub_category_id" id="childsubcategory_id">
                                            <option value="">Select Child SubCategory</option>
                                            @foreach($childsubcategories as $child)
                                            <option value="{{ $child->id }}"
                                                {{ $product->child_sub_category_id == $child->id ? 'selected' : '' }}>
                                                {{ $child->title }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-3">
                                    <button type="button" class="btn btn-primary next-tab"
                                            data-next="#general">Next</button>
                                </div>
                            </div>
                            <!-- ✅ General Tab -->
                            <div class="tab-pane fade" id="general" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6 col-lg-4">
                                        <label>Isbn:</label>
                                        <input type="text" name="sku" class="form-control" value="{{ $product->sku }}"
                                            readonly>
                                    </div>

                                    <div class="col-md-6 col-lg-4">
                                        <label>Name:</label>
                                        <input type="text" name="name" class="form-control"
                                            value="{{ $product->name }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-lg-4">
                                        <label>language:</label>
                                        <input type="text" name="language" class="form-control" value="{{ $product->language }}"
                                         >
                                    </div>

                                    <div class="col-md-6 col-lg-4">
                                        <label>Edition:</label>
                                        <input type="text" name="edition" class="form-control"
                                            value="{{ $product->edition }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-lg-4">
                                        <label>Published Date:</label>
                                        <input type="date" name="published_date" class="form-control" value="{{ $product->published_date }}"
                                            >
                                    </div>
                                      <div class="col-md-6 col-lg-4">
                                        <label>Item Stock:</label>
                                        <input type="text" name="stock" class="form-control"
                                            value="{{ $product->stock }}">
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-lg-4">
                                        <label>Author:</label>
                                        <input type="text" name="author" class="form-control" value="{{ $product->author }}"
                                            >
                                    </div>
                                      <div class="col-md-6 col-lg-4">
                                        <label>Publisher:</label>
                                        <input type="text" name="publisher" class="form-control"
                                            value="{{ $product->publisher }}">
                                    </div>
                                    
                                </div>
                                <!-- Product Options -->
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="product_on_sale"
                                                id="jewellery_on_sale" value="1"
                                                {{ old('product_on_sale', $product->product_on_sale) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="jewellery_on_sale">Best seller product</label>
                                            <div><small class="text-muted">Enable Best seller product</small></div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="new_arrivals"
                                                id="new_arrivals" value="1"
                                                {{ old('new_arrivals', $product->new_arrivals) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="new_arrivals">New Arrivals
                                                Product</label>
                                            <div><small class="text-muted">Enable New Arrivals Product</small></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="exam_corner"
                                                id="exam_corner" value="1"
                                                {{ old('exam_corner', $product->exam_corner) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="jewellery_on_sale"> Exam corner product</label>
                                            <div><small class="text-muted"> Exam corner product</small></div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="featured_book"
                                                id="featured_book" value="1"
                                                {{ old('featured_book', $product->featured_book) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="featured_book">Featured books</label>
                                            <div><small class="text-muted">Featured books</small></div>
                                        </div>
                                    </div>

                                    
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label>Simple PDF</label>
                                            <input type="file" name="product_pdf" class="form-control" accept=".pdf" />
                                            @error('product_pdf')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror

                                            @if($product->product_pdf)
                                                <div class="mt-2">
                                                    <a href="{{ asset('userassets/image/product/pdf/' . $product->product_pdf) }}" 
                                                    target="_blank" class="btn btn-sm btn-info">
                                                        📄 View PDF
                                                    </a>
                                                    <a href="{{ route('product.image.remove', ['product_id' => $product->id, 'field' => 'product_pdf']) }}"
                                                    class="btn btn-sm btn-danger mt-1"
                                                    onclick="return confirm('Are you sure you want to remove this PDF?')">
                                                        🗑️ Remove PDF
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>   
                                 <!-- Description -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Description:</label>
                                            <textarea class="form-control" name="description" id="description"
                                                rows="5">{{ old('description', $product->description) }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-3">
                                    <button type="button" class="btn btn-secondary prev-tab"
                                        data-prev="#categories">Previous</button>
                                    <button type="button" class="btn btn-primary next-tab"
                                        data-next="#price">Next</button>
                                </div>
                            </div>
                           
                            <!-- Price TAB -->
                            <div class="tab-pane fade" id="price" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="mrp_price">MRP Price:*</label>
                                            <input type="number" step="0.01" min="0" name="mrp_price"
                                                class="form-control" id="mrp_price" value="{{ $product->mrp_price }}"
                                                required>
                                            @error('mrp_price')<small
                                                class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="display_price">Display Price:</label>
                                            <input type="number" step="0.01" min="0" name="display_price"
                                                class="form-control" id="display_price"
                                                value="{{ $product->display_price }}" readonly>
                                            @error('display_price')<small
                                                class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Discount Checkbox - Always show this -->
                                <!-- Discount Checkbox -->
                                <div class="row">
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" name="apply_discount"
                                                id="apply_discount" value="1" {{ $product->discount ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="apply_discount">
                                                Apply Discount
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Discount Field -->
                                <div id="discount_field" style="display: {{ $product->discount ? 'block' : 'none' }};">
                                    <div class="row">
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <label for="discount">Discount (%):</label>
                                                <input type="number" step="1" min="0" max="100" name="discount"
                                                    class="form-control" id="discount"
                                                    value="{{ $product->discount ?? '' }}" placeholder="Enter Discount">
                                                <small id="discount-error" class="text-danger"></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Shipping Options -->
<div class="row mt-3">
    <div class="col-md-6 col-lg-4">
        <label class="fw-bold">Shipping Type:</label>

      <div class="form-check">
    <input type="radio" name="shipping_type" id="free_shipping" value="0"
        {{ old('shipping_type', $product->shipping_type) == 0 ? 'checked' : '' }}>
    <label class="form-check-label">Free Shipping</label>
</div>

<div class="form-check">
    <input type="radio" name="shipping_type" id="cost_shipping" value="1"
        {{ old('shipping_type', $product->shipping_type) == 1 ? 'checked' : '' }}>
    <label class="form-check-label">Cost Shipping</label>
</div>
    </div>
</div>

<!-- Shipping Charge -->
<div class="row mt-2" id="shipping_charge_field"
    style="display: {{ old('shipping_type', $product->shipping_type) == 1 ? 'block' : 'none' }};">
    
    <div class="col-md-6 col-lg-4">
        <label>Shipping Charge:</label>
        <input type="number" name="shipping_charge" id="shipping_charge"
            class="form-control"
            value="{{ old('shipping_charge', $product->shipping_charge) }}"
            placeholder="Enter Shipping Charge">
    </div>
</div>
<div class="row mt-3">
  <div class="col-md-6 col-lg-4">
    <div class="d-flex align-items-center gap-3">
      <label class="switch mb-0">
        <input type="checkbox"
               name="cod_available"
               id="cod_available"
               value="1"
               {{ old('cod_available', $product->cod_available) == '1' ? 'checked' : '' }}>
        <span class="slider round"></span>
      </label>
      <div>
        <label class="form-check-label fw-bold mb-0" for="cod_available">
          COD Available
        </label>
        <div><small class="text-muted">Enable Cash on Delivery for this product</small></div>
      </div>
    </div>
  </div>
</div>

                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <button type="button" class="btn btn-secondary prev-tab"
                                        data-prev="#general">Previous</button>
                                    <button type="button" class="btn btn-primary next-tab"
                                        data-next="#images">Next</button>
                                </div>
                            </div>
                            <!-- Images TAB -->
                            <div class="tab-pane fade" id="images" role="tabpanel">
                                <div class="row">
                                    <!-- Main Image -->
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label>Upload Image</label>
                                            <input type="file" name="image" class="form-control" />
                                            @error('image')<small class="text-danger">{{ $message }}</small>@enderror
                                            <small id="image-error" class="text-danger"></small>

                                            @if($product->image)
                                            <img src="{{ asset('userassets/image/product/' . $product->image) }}"
                                                height="50" class="mt-2"><br>
                                            <a href="{{ route('product.image.remove', ['product_id' => $product->id, 'field' => 'image']) }}"
                                                class="btn btn-sm btn-danger mt-2"
                                                onclick="return confirm('Are you sure you want to remove this image?')">
                                                Remove Image
                                            </a>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Image 2 -->
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label>Upload Image 2</label>
                                            <input type="file" name="image2" class="form-control" />
                                            @if($product->image2)
                                            <img src="{{ asset('userassets/image/product/' . $product->image2) }}"
                                                height="50" class="mt-2"><br>
                                            <a href="{{ route('product.image.remove', ['product_id' => $product->id, 'field' => 'image2']) }}"
                                                class="btn btn-sm btn-danger mt-2"
                                                onclick="return confirm('Are you sure you want to remove this image?')">
                                                Remove Image
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Image 3 -->
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label>Upload Image 3</label>
                                            <input type="file" name="image3" class="form-control" />
                                            @if($product->image3)
                                            <img src="{{ asset('userassets/image/product/' . $product->image3) }}"
                                                height="50" class="mt-2"><br>
                                            <a href="{{ route('product.image.remove', ['product_id' => $product->id, 'field' => 'image3']) }}"
                                                class="btn btn-sm btn-danger mt-2"
                                                onclick="return confirm('Are you sure you want to remove this image?')">
                                                Remove Image
                                            </a>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Image 4 -->
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label>Upload Image 4</label>
                                            <input type="file" name="image4" class="form-control" />
                                            @if($product->image4)
                                            <img src="{{ asset('userassets/image/product/' . $product->image4) }}"
                                                height="50" class="mt-2"><br>
                                            <a href="{{ route('product.image.remove', ['product_id' => $product->id, 'field' => 'image4']) }}"
                                                class="btn btn-sm btn-danger mt-2"
                                                onclick="return confirm('Are you sure you want to remove this image?')">
                                                Remove Image
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Image 5 -->
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label>Upload Image 5</label>
                                            <input type="file" name="image5" class="form-control" />
                                            @if($product->image5)
                                            <img src="{{ asset('userassets/image/product/' . $product->image5) }}"
                                                height="50" class="mt-2"><br>
                                            <a href="{{ route('product.image.remove', ['product_id' => $product->id, 'field' => 'image5']) }}"
                                                class="btn btn-sm btn-danger mt-2"
                                                onclick="return confirm('Are you sure you want to remove this image?')">
                                                Remove Image
                                            </a>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Multiple Images -->
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label>Upload Multiple Images</label>
                                            <input type="file" name="multipleimage[]" class="form-control" multiple />
                                            @php
                                                $multipleImages = is_array($product->multipleimage) 
                                                    ? $product->multipleimage 
                                                    : json_decode($product->multipleimage, true) ?? [];
                                            @endphp

                                            @if(!empty($multipleImages))
                                                <div class="mt-2">
                                                    @foreach($multipleImages as $img)
                                                        <div class="d-flex align-items-center mb-1">
                                                            <img src="{{ asset('userassets/image/product/' . $img) }}" height="50" class="me-2">
                                                            <a href="{{ route('product.image.remove', ['product_id' => $product->id, 'field' => 'multipleimage', 'image_name' => $img]) }}"
                                                            class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Are you sure you want to remove this image?')">
                                                                Remove
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                
                                 <div class="d-flex justify-content-between mt-3">
                                    <button type="button" class="btn btn-secondary prev-tab"
                                        data-prev="#price">Previous</button>
                                    <button type="submit" class="btn btn-success">💾 Update Product</button>
                                </div>
                            </div>

                            

                        </div>
                    </div>
                </form>


            </div>
        </div>
    </div>
</div>
</div>
<style>
.cke_notifications_area {
    display: none !important;
}
.switch {
    position: relative;
    display: inline-block;
    width: 52px;
    height: 26px;
}
.switch input { opacity: 0; width: 0; height: 0; }
.slider {
    position: absolute;
    cursor: pointer;
    top: 0; left: 0; right: 0; bottom: 0;
    background-color: #ccc;
    transition: .4s;
}
.slider:before {
    position: absolute;
    content: "";
    height: 20px; width: 20px;
    left: 3px; bottom: 3px;
    background-color: white;
    transition: .4s;
}
input:checked + .slider { background-color: #2196F3; }
input:checked + .slider:before { transform: translateX(26px); }
.slider.round { border-radius: 26px; }
.slider.round:before { border-radius: 50%; }
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const applyDiscountCheckbox = document.getElementById('apply_discount');
    const discountField = document.getElementById('discount_field');
    const discountInput = document.getElementById('discount');
    const displayInput = document.getElementById('display_price');
    const mrpInput = document.getElementById('mrp_price');

    // Set display price to always be readonly
    displayInput.readOnly = true;

    // Initial setup based on existing data
    function initializePriceFields() {
        // If product has discount, show the field and calculate
        @if($product->discount)
            discountField.style.display = 'block';
            applyDiscountCheckbox.checked = true;
            calculateDisplayPrice(); // Calculate on page load
        @else
            discountField.style.display = 'none';
            // Clear display price if no discount
            displayInput.value = '';
        @endif
    }

    // Call initialization
    initializePriceFields();

    // Toggle discount field visibility
    applyDiscountCheckbox.addEventListener('change', function() {
        if (this.checked) {
            discountField.style.display = 'block';
            // When checked, calculate price
            calculateDisplayPrice();
        } else {
            discountField.style.display = 'none';
            discountInput.value = ''; // Clear discount input
            displayInput.value = ''; // Clear display price
        }
    });

    // Calculate display price based on MRP and discount
    function calculateDisplayPrice() {
        const mrp = parseFloat(mrpInput.value) || 0;
        const discount = parseFloat(discountInput.value) || 0;
        
        if (applyDiscountCheckbox.checked && mrp > 0) {
            if (discount > 0 && discount <= 100) {
                const discountedPrice = mrp - (mrp * discount / 100);
                displayInput.value = discountedPrice.toFixed(2);
            } else {
                // If discount is 0 or invalid, show MRP
                displayInput.value = mrp.toFixed(2);
            }
        } else {
            // If checkbox unchecked, clear display price
            displayInput.value = '';
        }
    }

    // Event listeners for real-time calculation
    discountInput.addEventListener('input', function() {
        // Validate discount range
        let val = parseFloat(this.value) || 0;
        if (val > 100) {
            this.value = 100;
            val = 100;
        }
        if (val < 0) {
            this.value = 0;
            val = 0;
        }
        
        if (applyDiscountCheckbox.checked) {
            calculateDisplayPrice();
        }
    });

    mrpInput.addEventListener('input', function() {
        if (applyDiscountCheckbox.checked) {
            calculateDisplayPrice();
        } else {
            displayInput.value = '';
        }
    });

    // Input validation for price fields
    function validatePriceInput(inputId) {
        const input = document.getElementById(inputId);
        input.addEventListener('input', function() {
            // Allow only numbers and one decimal point
            this.value = this.value.replace(/[^0-9.]/g, '');
            // Ensure only one decimal point
            if ((this.value.match(/\./g) || []).length > 1) {
                this.value = this.value.substring(0, this.value.lastIndexOf('.'));
            }
        });
    }

    validatePriceInput('mrp_price');
    validatePriceInput('discount');
});
</script>

<!-- CKEditor -->
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
CKEDITOR.replace('description');
</script>

<!-- Feature Add/Remove JS -->
<script>

document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.remove-image-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (!confirm('Are you sure you want to remove this image?')) return;

                    const field = this.getAttribute('data-field');
                    const product_id = this.getAttribute('data-product');
                    const image_name = this.getAttribute('data-image');

                    fetch("{{ route('product.remove.image') }}", {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify({
                                field,
                                product_id,
                                image_name
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                alert(data.message);
                                location.reload();
                            } else {
                                alert('Failed to remove image!');
                            }
                        })
                });
            });
</script>

<!-- Tabs Navigation -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".next-tab").forEach(btn => {
        btn.addEventListener("click", function() {
            let nextTab = this.getAttribute("data-next");
            new bootstrap.Tab(document.querySelector(`button[data-bs-target="${nextTab}"]`))
                .show();
        });
    });

    document.querySelectorAll(".prev-tab").forEach(btn => {
        btn.addEventListener("click", function() {
            let prevTab = this.getAttribute("data-prev");
            new bootstrap.Tab(document.querySelector(`button[data-bs-target="${prevTab}"]`))
                .show();
        });
    });
});
</script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Category → Subcategory → Child Subcategory -->
<script>
$(document).ready(function() {
    $('#exampleFormControlSelect1').on('change', function() {
        var categoryId = $(this).val();
        if (categoryId) {
            $.ajax({
                url: '/admin/get-subcategories/' + categoryId,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    $('#subcategory').empty().append(
                        '<option value="">Select Subcategory</option>');
                    $.each(data, function(key, value) {
                        $('#subcategory').append('<option value="' + value.id +
                            '">' + value.title + '</option>');
                    });
                    $('#childsubcategory').empty().append(
                        '<option value="">Select Child SubCategory</option>');
                }
            });
        } else {
            $('#subcategory').empty().append('<option value="">Select Subcategory</option>');
            $('#childsubcategory').empty().append('<option value="">Select Child SubCategory</option>');
        }
    });

    $('#subcategory').on('change', function() {
        var subcategoryId = $(this).val();
        if (subcategoryId) {
            $.ajax({
                url: '/admin/get-childsubcategories/' + subcategoryId,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    $('#childsubcategory').empty().append(
                        '<option value="">Select Child SubCategory</option>');
                    $.each(data, function(key, value) {
                        $('#childsubcategory').append('<option value="' + value.id +
                            '">' + value.title + '</option>');
                    });
                }
            });
        } else {
            $('#childsubcategory').empty().append('<option value="">Select Child SubCategory</option>');
        }
    });
});
</script>




<script>
document.getElementById('imageInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file && file.size > 2 * 1024 * 1024) {
        document.getElementById('imageError').classList.remove('d-none');
        e.target.value = '';
    } else {
        document.getElementById('imageError').classList.add('d-none');
    }
});

document.getElementById('multiImageInput').addEventListener('change', function(e) {
    const files = e.target.files;
    for (let i = 0; i < files.length; i++) {
        if (files[i].size > 2 * 1024 * 1024) {
            document.getElementById('multiImageError').classList.remove('d-none');
            e.target.value = '';
            return;
        }
    }
    document.getElementById('multiImageError').classList.add('d-none');
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const freeShipping = document.getElementById('free_shipping');
    const costShipping = document.getElementById('cost_shipping');
    const shippingField = document.getElementById('shipping_charge_field');
    const shippingInput = document.getElementById('shipping_charge');

    function toggleShippingField() {
        if (costShipping.checked) {
            shippingField.style.display = 'block';
        } else {
            shippingField.style.display = 'none';
            shippingInput.value = '';
        }
    }

    // Page load pe run
    toggleShippingField();

    freeShipping.addEventListener('change', toggleShippingField);
    costShipping.addEventListener('change', toggleShippingField);
});
</script>
@endsection