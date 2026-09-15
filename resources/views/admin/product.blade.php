@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Create Product</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="#"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="#">Forms</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="#">New Product</a></li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="card-header">
                            <div class="card-title">New Product</div>
                        </div>

                        <div class="card-body">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item"><button class="nav-link active" id="categories-tab"
                                        data-bs-toggle="tab" data-bs-target="#categories" type="button"
                                        role="tab">Categories</button></li>
                                <li class="nav-item"><button class="nav-link" id="images-tab" data-bs-toggle="tab"
                                        data-bs-target="#images" type="button" role="tab">Images</button></li>
                                <li class="nav-item"><button class="nav-link" id="general-tab" data-bs-toggle="tab"
                                        data-bs-target="#general" type="button" role="tab">General</button></li>
                                <li class="nav-item"><button class="nav-link" id="price-tab" data-bs-toggle="tab"
                                        data-bs-target="#price" type="button" role="tab">price</button></li>
                               
                            </ul>

                            <!-- TAB CONTENT -->
                            <div class="tab-content mt-3" id="myTabContent">

                                <!-- Categories TAB -->
                                <div class="tab-pane fade show active" id="categories" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <label for="exampleFormControlSelect1">Select Category</label>
                                                <select class="form-select" name="category_id" id="exampleFormControlSelect1">
                                                    <option value="">Select Category</option>
                                                    @foreach($categories as $cg)
                                                    <option value="{{$cg->id}}" {{ old('category_id') == $cg->id ? 'selected' : '' }}>
                                                        {{$cg->title}}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                @error('category_id')<small class="text-danger">{{ $message }}</small>@enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <label>Select Subcategory</label>
                                                <select class="form-select" id="subcategory" name="sub_category_id">
                                                    <option value="">Select Subcategory</option>
                                                    @if(old('sub_category_id'))
                                                        @php
                                                            $categoryId = old('category_id');
                                                            $subcategories = \App\Models\Subcategory::where('category_id', $categoryId)->get();
                                                        @endphp
                                                        @foreach($subcategories as $subcat)
                                                            <option value="{{ $subcat->id }}" {{ old('sub_category_id') == $subcat->id ? 'selected' : '' }}>
                                                                {{ $subcat->title }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('sub_category_id')<small class="text-danger">{{ $message }}</small>@enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <label for="childsubcategory">Select Child SubCategory</label>
                                                <select class="form-select" name="child_sub_category_id" id="childsubcategory">
                                                    <option value="">Select Child SubCategory</option>
                                                    @if(old('child_sub_category_id'))
                                                        @php
                                                            $subcategoryId = old('sub_category_id');
                                                            $childsubcategories = \App\Models\ChildSubCategory::where('sub_category_id', $subcategoryId)->get();
                                                        @endphp
                                                        @foreach($childsubcategories as $childcat)
                                                            <option value="{{ $childcat->id }}" {{ old('child_sub_category_id') == $childcat->id ? 'selected' : '' }}>
                                                                {{ $childcat->title }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('child_sub_category_id')<small class="text-danger">{{ $message }}</small>@enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <button type="button" class="btn btn-secondary prev-tab" data-prev="#categories">Previous</button>
                                        <button type="button" class="btn btn-primary next-tab" data-next="#images">Next</button>
                                    </div>
                                </div>

                                <!-- Images TAB -->
                                <div class="tab-pane fade" id="images" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <label>Upload Image</label>
                                                <input type="file" name="image" class="form-control" />
                                                @error('image')<small class="text-danger">{{ $message }}</small>@enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <label>Alt Tag</label>
                                                <input type="text" name="altimage" class="form-control" value="{{ old('altimage') }}" />
                                                @error('altimage')<small class="text-danger">{{ $message }}</small>@enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <label>Upload Image 2</label>
                                                <input type="file" name="image2" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <label>Alt Tag</label>
                                                <input type="text" name="altimage2" class="form-control" value="{{ old('altimage2') }}" />
                                                @error('altimage2')<small class="text-danger">{{ $message }}</small>@enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <label>Upload Image 3</label>
                                                <input type="file" name="image3" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <label>Alt Tag</label>
                                                <input type="text" name="altimage3" class="form-control" value="{{ old('altimage3') }}" />
                                                @error('altimage3')<small class="text-danger">{{ $message }}</small>@enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <label>Upload Image 4</label>
                                                <input type="file" name="image4" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <label>Alt Tag</label>
                                                <input type="text" name="altimage4" class="form-control" value="{{ old('altimage4') }}" />
                                                @error('altimage4')<small class="text-danger">{{ $message }}</small>@enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <label>Upload Image 5</label>
                                                <input type="file" name="image5" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <label>Alt Tag</label>
                                                <input type="text" name="altimage5" class="form-control" value="{{ old('altimage5') }}" />
                                                @error('altimage5')<small class="text-danger">{{ $message }}</small>@enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <label>Upload Multiple Image</label>
                                                <input type="file" name="multipleimage[]" class="form-control" multiple />
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="auto_filled_from_multiple" id="auto_filled_from_multiple" value="0">
                                    
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <button type="button" class="btn btn-secondary prev-tab" data-prev="#categories">Previous</button>
                                        <button type="button" class="btn btn-primary next-tab" data-next="#general">Next</button>
                                    </div>
                                </div>

                                <!-- General TAB -->
                                <div class="tab-pane fade" id="general" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <label for="sku">Isbn:*</label>
                                                <input type="text" class="form-control" name="sku" id="sku"
                                                    placeholder="Enter Isbn" value="{{ old('sku') }}" readonly />
                                                @error('sku')<small class="text-danger">{{ $message }}</small>@enderror
                                                <small id="sku-error" class="text-danger"></small>
                                            </div>
                                        </div>
                                         <div class="col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <label for="name">Name:*</label>
                                                <input type="text" name="name" class="form-control" id="name"
                                                    placeholder="Enter Name" value="{{ old('name') }}" />
                                                @error('name')<small class="text-danger">{{ $message }}</small>@enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <label for="tag">language:</label>
                                                <input type="text" class="form-control" name="language" id="language"
                                                    placeholder="Enter language" value="{{ old('language') }}" />
                                                    @error('language')<small class="text-danger">{{ $message }}</small>@enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <label for="stock">Item Stock:*</label>
                                                <input type="text" name="stock" class="form-control" id="stock"
                                                    placeholder="Enter Item Stock" value="{{ old('stock') }}" />
                                                @error('stock')<small class="text-danger">{{ $message }}</small>@enderror
                                               
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <label for="edition">Edition:</label>
                                                <input type="text" class="form-control" name="edition" id="edition"
                                                    placeholder="Enter Edition" value="{{ old('edition') }}" />
                                                    @error('edition')<small class="text-danger">{{ $message }}</small>@enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <label for="published_date">Published Date*</label>
                                                <input type="date" name="published_date" class="form-control" id="published_date"
                                                    placeholder="Enter Published Date" value="{{ old('published_date') }}" />
                                                @error('published_date')<small id="published_date-error"  class="text-danger">{{ $message }}</small>@enderror
                                                
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Colors Multiselect -->
                                    <div class="row">
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <label for="tag">Author:</label>
                                                <input type="text" class="form-control" name="author" id="author"
                                                    placeholder="Enter author" value="{{ old('author') }}" />
                                                    @error('author')<small class="text-danger">{{ $message }}</small>@enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <label for="stock">Publisher:*</label>
                                                <input type="text" name="publisher" class="form-control" id="publisher"
                                                    placeholder="Enter publisher" value="{{ old('publisher') }}" />
                                                @error('publisher')<small class="text-danger">{{ $message }}</small>@enderror
                                                <small id="publisher-error" class="text-danger"></small>
                                            </div>
                                        </div>
                                    </div>

                                   
                                    <!-- Product Options Section -->
                                    <div class="row">
                                        <div class="col-md-3 col-lg-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="product_on_sale"
                                                    id="jewellery_on_sale" value="1"
                                                    {{ old('product_on_sale') == '1' ? 'checked' : '' }}>
                                                <label class="form-check-label fw-bold" for="jewellery_on_sale">
                                                    Best seller product
                                                </label>
                                                <div><small class="text-muted">Enable Best seller product</small></div>
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-lg-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="new_arrivals"
                                                    id="new_arrivals" value="1"
                                                    {{ old('new_arrivals') == '1' ? 'checked' : '' }}>
                                                <label class="form-check-label fw-bold" for="new_arrivals">
                                                    New arrivals product
                                                </label>
                                                <div><small class="text-muted">Enable New arrivals product</small></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-lg-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="exam_corner"
                                                    id="exam_corner" value="1"
                                                    {{ old('exam_corner') == '1' ? 'checked' : '' }}>
                                                <label class="form-check-label fw-bold" for="exam_corner">
                                                    Exam corner product
                                                </label>
                                                <div><small class="text-muted">Enable Exam corner product</small></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-lg-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="featured_book"
                                                    id="featured_book" value="1"
                                                    {{ old('featured_book') == '1' ? 'checked' : '' }}>
                                                <label class="form-check-label fw-bold" for="featured_book">
                                                    Featured books product
                                                </label>
                                                <div><small class="text-muted">Enable Featured books product</small></div>
                                            </div>
                                        </div>

                                       
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <label>Simple Pdf</label>
                                                <input type="file" name="product_pdf" class="form-control" />
                                                @error('product_pdf')<small class="text-danger">{{ $message }}</small>@enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Description:</label>
                                                <textarea class="form-control" name="description" id="description" rows="5">{{ old('description') }}</textarea>
                                                @error('description')<small class="text-danger">{{ $message }}</small>@enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <button type="button" class="btn btn-secondary prev-tab" data-prev="#images">Previous</button>
                                        <button type="button" class="btn btn-primary next-tab" data-next="#price">Next</button>
                                    </div>
                                </div>

                                <!-- Price TAB -->
                                <div class="tab-pane fade" id="price" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <label for="mrp_price">MRP Price:*</label>
                                                <input type="text" class="form-control" name="mrp_price" id="mrp_price"
                                                    placeholder="Enter MRP" value="{{ old('mrp_price') }}" />
                                                @error('mrp_price')<small class="text-danger">{{ $message }}</small>@enderror
                                                <small id="mrp_price-error" class="text-danger"></small>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <label for="display_price">Display Price:*</label>
                                                <input type="text" name="display_price" class="form-control"
                                                    id="display_price" placeholder="Enter Display Price" value="{{ old('display_price') }}" />
                                                @error('display_price')<small class="text-danger">{{ $message }}</small>@enderror
                                                <small id="display_price-error" class="text-danger"></small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Discount Checkbox -->
                                    <div class="row">
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" name="apply_discount"
                                                    id="apply_discount" value="1" {{ old('apply_discount') == '1' ? 'checked' : '' }}>
                                                <label class="form-check-label fw-bold" for="apply_discount">
                                                    Apply Discount
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Discount Field (Initially Hidden) -->
                                    <div id="discount_field" style="display: {{ old('apply_discount') == '1' ? 'block' : 'none' }};">
                                        <div class="row">
                                            <div class="col-md-6 col-lg-4">
                                                <div class="form-group">
                                                    <label for="discount">Discount (%):</label>
                                                    <input type="text" name="discount" class="form-control"
                                                        id="discount" placeholder="Enter Discount" value="{{ old('discount') }}" />
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
            <input class="form-check-input" type="radio" name="shipping_type" id="free_shipping" value="0"
    {{ old('shipping_type') == '0' ? 'checked' : '' }}>
            <label class="form-check-label" for="free_shipping">
                Free Shipping
            </label>
        </div>

        <div class="form-check">
            <input class="form-check-input" type="radio" name="shipping_type" id="cost_shipping" value="1"
    {{ old('shipping_type') == '1' ? 'checked' : '' }}>
            <label class="form-check-label" for="cost_shipping">
                Cost Shipping
            </label>
        </div>

        @error('shipping_type')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<!-- Shipping Charge Field -->
<div class="row mt-2" id="shipping_charge_field"
    style="display: {{ old('shipping_type') == 'cost' ? 'block' : 'none' }};">
    
    <div class="col-md-6 col-lg-4">
        <div class="form-group">
            <label>Shipping Charge:</label>
            <input type="text" name="shipping_charge" id="shipping_charge"
                class="form-control"
                value="{{ old('shipping_charge') }}"
                placeholder="Enter Shipping Charge">

            @error('shipping_charge')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
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
               {{ old('cod_available') == '1' ? 'checked' : '' }}>
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
                                        <button type="button" class="btn btn-secondary prev-tab" data-prev="#price">Previous</button>
                                        <div>
                                            <button type="submit" class="btn btn-success me-2" id="saveFinishBtn">Save & Finish</button>
                                            <button type="button" class="btn btn-danger" id="cancelBtn">Cancel</button>
                                        </div>
                                        <button type="button" class="btn btn-primary next-tab" style="visibility: hidden;">Next</button>
                                    </div>
                                </div>

                                <!-- Meta TAB -->
                               
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
<!-- CKEditor -->
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
// Initialize CKEditor with old values
CKEDITOR.replace('description', {
    on: {
        instanceReady: function() {
            this.setData(@json(old('description', '')));
        }
    }
});

</script>




<script>
    // ============================================
// Tab Validation & Navigation (Client-side)
// ============================================
document.addEventListener("DOMContentLoaded", function() {
    // Define required fields per tab (by field name or selector)
    const tabRequiredFields = {
        'categories': ['category_id'], // only category is required
        'images': ['image'],           // main image required
        'general': ['sku', 'name', 'stock', 'language','edition', 'published_date', 'author','publisher','description' ],
        'price': ['mrp_price']
    };

    // Helper: get field element by name within a specific tab
    function getFieldInTab(tabId, fieldName) {
        const tab = document.getElementById(tabId);
        if (!tab) return null;
        
        if (fieldName === 'description') {
            // CKEditor instance – we'll handle separately
            return CKEDITOR.instances.description;
        }
        //return tab.querySelector(`[name="${fieldName}"]`);
        return tab.querySelector(`[name="${fieldName}"], [name="${fieldName}[]"]`);
    }

    // Show error message for a field
    function showFieldError(field, message) {
        // Remove any existing client error for this field
        clearFieldClientError(field);

        // Create error element
        const error = document.createElement('small');
        error.className = 'text-danger client-error';
        error.textContent = message;

        // Insert after the field
        if (field.nodeName === 'INPUT' || field.nodeName === 'SELECT' || field.nodeName === 'TEXTAREA') {
            field.classList.add('is-invalid');
            field.parentNode.appendChild(error);
        } else if (field instanceof CKEDITOR.editor) {
            // For CKEditor, add error after the editor container
            const editorContainer = field.container.$;
            const wrapper = editorContainer.closest('.form-group') || editorContainer.parentNode;
            field.classList.add('is-invalid'); // not standard, but we can add to textarea
            wrapper.appendChild(error);
        }
    }

    // Clear client error for a specific field
    function clearFieldClientError(field) {
        if (field.nodeName === 'INPUT' || field.nodeName === 'SELECT' || field.nodeName === 'TEXTAREA') {
            field.classList.remove('is-invalid');
            const parent = field.parentNode;
            const errors = parent.querySelectorAll('.client-error');
            errors.forEach(e => e.remove());
        } else if (field instanceof CKEDITOR.editor) {
            const editorContainer = field.container.$;
            const wrapper = editorContainer.closest('.form-group') || editorContainer.parentNode;
            const errors = wrapper.querySelectorAll('.client-error');
            errors.forEach(e => e.remove());
            // remove class from original textarea
            const textarea = document.getElementById(field.name);
            if (textarea) textarea.classList.remove('is-invalid');
        }
    }

    // Clear all client errors in a tab
    function clearTabClientErrors(tabId) {
        const tab = document.getElementById(tabId);
        if (!tab) return;
        tab.querySelectorAll('.client-error').forEach(e => e.remove());
        tab.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    }

    // Validate a single field (return true if valid)
    // function validateField(field, fieldName) {
        

    //     if (fieldName === 'description') {
    //         // CKEditor instance
    //         if (!field || field.getData().trim() === '') {
    //             showFieldError(field, 'Description is required.');
    //             return false;
    //         }
    //         return true;
    //     }

    //     // Regular input/select/file
    //     if (!field) return false;
    //     let value;
    //     if (field.type === 'file') {
    //         value = field.files.length > 0;
    //     } else {
    //         value = field.value.trim() !== '';
    //     }
    //     if (!value) {
    //         showFieldError(field, 'This field is required.');
    //         return false;
    //     }
    //     return true;
    // }
    function validateField(field, fieldName) {

    if (fieldName === 'description') {
        if (!field || field.getData().trim() === '') {
            showFieldError(field, 'This field is required.');
            return false;
        }
        return true;
    }

    // RADIO GROUP VALIDATION
    if (fieldName === 'colors[]' || fieldName === 'sizes[]') {
        const checked = document.querySelector(`input[name="${fieldName}"]:checked`);
        if (!checked) {
            const container = document.querySelector(`input[name="${fieldName}"]`).closest('.form-group');
            const error = document.createElement('small');
            error.className = 'text-danger client-error';
            error.textContent = 'This field is required.';
            container.appendChild(error);
            return false;
        }
        return true;
    }

    if (!field) return false;

    let value;

    if (field.type === 'file') {
        value = field.files.length > 0;
    } else {
        value = field.value.trim() !== '';
    }

    if (!value) {
        showFieldError(field, 'This field is required.');
        return false;
    }

    return true;
}

    // Validate all required fields in a tab, return true if all valid
    function validateTab(tabId) {
        clearTabClientErrors(tabId);
        const required = tabRequiredFields[tabId];
        if (!required) return true; // no required fields for this tab

        let allValid = true;
        required.forEach(fieldName => {
            const field = getFieldInTab(tabId, fieldName);
            if (!field) return; // field not found – maybe ignore
            if (!validateField(field, fieldName)) {
                allValid = false;
            }
        });
        return allValid;
    }

    // ---- Next/Prev button handling ----
    document.querySelectorAll(".next-tab").forEach(btn => {
        btn.addEventListener("click", function(e) {
            e.preventDefault();
            const currentTabId = this.closest('.tab-pane').id;
            const nextTabSelector = this.getAttribute("data-next");
            if (!nextTabSelector) return;

            // Validate current tab
            if (!validateTab(currentTabId)) {
                // Validation failed – stay on current tab
                return;
            }

            // Switch to next tab
            const nextTabBtn = document.querySelector(`button[data-bs-target="${nextTabSelector}"]`);
            if (nextTabBtn) {
                bootstrap.Tab.getInstance(nextTabBtn)?.show() || new bootstrap.Tab(nextTabBtn).show();
            }
        });
    });

    // Previous buttons – no validation needed, just switch
    document.querySelectorAll(".prev-tab").forEach(btn => {
        btn.addEventListener("click", function(e) {
            e.preventDefault();
            const prevTabSelector = this.getAttribute("data-prev");
            if (prevTabSelector) {
                const prevTabBtn = document.querySelector(`button[data-bs-target="${prevTabSelector}"]`);
                if (prevTabBtn) {
                    bootstrap.Tab.getInstance(prevTabBtn)?.show() || new bootstrap.Tab(prevTabBtn).show();
                }
            }
        });
    });

    // ---- Tab click validation (intercept show.bs.tab) ----
    document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(tabBtn => {
        tabBtn.addEventListener('show.bs.tab', function(e) {
            // e.target = tab being shown, e.relatedTarget = previously active tab
            const previousTabBtn = e.relatedTarget;
            if (!previousTabBtn) return; // no previous tab (first load)

            const previousTabId = previousTabBtn.getAttribute('data-bs-target')?.substring(1);
            if (!previousTabId) return;

            // Validate the tab we are leaving
            if (!validateTab(previousTabId)) {
                e.preventDefault(); // cancel tab switch
            }
        });
    });

    // ---- Clear field error on input/change ----
    document.querySelectorAll('input, select, textarea').forEach(field => {
        field.addEventListener('input', function() {
            clearFieldClientError(this);
        });
        field.addEventListener('change', function() {
            clearFieldClientError(this);
        });
    });
    // CKEditor change detection
    if (typeof CKEDITOR !== 'undefined') {
        CKEDITOR.on('instanceReady', function() {
            CKEDITOR.instances.description.on('change', function() {
                clearFieldClientError(this);
            });
        });
    }
});
    </script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Category → Subcategory → Child Subcategory -->
<script>
$(document).ready(function() {
    // If category is already selected from old input, load its subcategories
    const selectedCategoryId = $('#exampleFormControlSelect1').val();
    if (selectedCategoryId) {
        loadSubcategories(selectedCategoryId);
    }

    // If subcategory is already selected from old input, load its child subcategories
    const selectedSubcategoryId = $('#subcategory').val();
    if (selectedSubcategoryId) {
        loadChildSubcategories(selectedSubcategoryId);
    }

    $('#exampleFormControlSelect1').on('change', function() {
        var categoryId = $(this).val();
        if (categoryId) {
            loadSubcategories(categoryId);
        } else {
            $('#subcategory').empty().append('<option value="">Select Subcategory</option>');
            $('#childsubcategory').empty().append('<option value="">Select Child SubCategory</option>');
        }
    });

    $('#subcategory').on('change', function() {
        var subcategoryId = $(this).val();
        if (subcategoryId) {
            loadChildSubcategories(subcategoryId);
        } else {
            $('#childsubcategory').empty().append('<option value="">Select Child SubCategory</option>');
        }
    });

    function loadSubcategories(categoryId) {
        $.ajax({
            url: '/admin/get-subcategories/' + categoryId,
            type: "GET",
            dataType: "json",
            success: function(data) {
                $('#subcategory').empty().append('<option value="">Select Subcategory</option>');
                $.each(data, function(key, value) {
                    $('#subcategory').append('<option value="' + value.id + '">' + value.title + '</option>');
                });
                
                // Select old value if exists
                const oldSubcategoryId = @json(old('sub_category_id', ''));
                if (oldSubcategoryId) {
                    $('#subcategory').val(oldSubcategoryId);
                }
                
                $('#childsubcategory').empty().append('<option value="">Select Child SubCategory</option>');
            }
        });
    }

    function loadChildSubcategories(subcategoryId) {
        $.ajax({
            url: '/admin/get-childsubcategories/' + subcategoryId,
            type: "GET",
            dataType: "json",
            success: function(data) {
                $('#childsubcategory').empty().append('<option value="">Select Child SubCategory</option>');
                $.each(data, function(key, value) {
                    $('#childsubcategory').append('<option value="' + value.id + '">' + value.title + '</option>');
                });
                
                // Select old value if exists
                const oldChildSubcategoryId = @json(old('child_sub_category_id', ''));
                if (oldChildSubcategoryId) {
                    $('#childsubcategory').val(oldChildSubcategoryId);
                }
            }
        });
    }
});
</script>



<script>
// Integer validation functions
function validateIntegerInput(inputId, errorId) {
    const input = document.getElementById(inputId);
    const error = document.getElementById(errorId);
    
    if (!input) return;
    
    input.addEventListener('input', function() {
        const value = this.value;
        if (!/^\d*$/.test(value)) {
            error.textContent = 'Only numbers are allowed!';
            this.value = value.replace(/\D/g, '');
        } else {
            error.textContent = '';
        }
    });
}

// Apply validation to all number fields
validateIntegerInput('display_price', 'display_price-error');
validateIntegerInput('stock', 'stock-error');
validateIntegerInput('mrp_price', 'mrp_price-error');
validateIntegerInput('discount', 'discount-error');
</script>

<script>
// Discount Checkbox Functionality
document.addEventListener("DOMContentLoaded", function() {
    const applyDiscountCheckbox = document.getElementById('apply_discount');
    const discountField = document.getElementById('discount_field');
    const discountInput = document.getElementById('discount');
    const displayInput = document.getElementById('display_price');
    const mrpInput = document.getElementById('mrp_price');

    // On page load, if discount checkbox was checked, setup the logic
    if (applyDiscountCheckbox.checked) {
        displayInput.readOnly = true;
        calculateDisplayPrice();
    }

    // Toggle discount field visibility and display price readonly state
    applyDiscountCheckbox.addEventListener('change', function() {
        if (this.checked) {
            discountField.style.display = 'block';
            displayInput.readOnly = true;
            calculateDisplayPrice();
        } else {
            discountField.style.display = 'none';
            displayInput.readOnly = false;
            discountInput.value = '';
        }
    });

    // Calculate display price when discount or MRP changes
    function calculateDisplayPrice() {
        if (!applyDiscountCheckbox.checked) return;

        const mrp = parseFloat(mrpInput.value) || 0;
        const discount = parseFloat(discountInput.value) || 0;

        if (mrp > 0 && discount >= 0 && discount <= 100) {
            const discountedPrice = mrp - (mrp * discount / 100);
            displayInput.value = Math.round(discountedPrice);
        } else {
            displayInput.value = '';
        }
    }

    // Event listeners for real-time calculation
    discountInput.addEventListener('input', function() {
        const val = parseFloat(this.value) || 0;
        if (val > 100) this.value = 100;
        if (val < 0) this.value = 0;
        calculateDisplayPrice();
    });

    mrpInput.addEventListener('input', calculateDisplayPrice);
});
</script>

<script>
// SKU Generation
document.addEventListener("DOMContentLoaded", function() {
    const categorySelect = document.getElementById("exampleFormControlSelect1");
    const nameInput = document.getElementById("name");
    const skuInput = document.getElementById("sku");

    // Only generate SKU if it's empty (not from old input)
    if (!skuInput.value) {
        function generateSKU() {
            const category = categorySelect.options[categorySelect.selectedIndex]?.text?.trim() || "";
            const name = nameInput.value.trim();

            if (category && name) {
                const formattedName = name.replace(/\s+/g, '').toUpperCase();
                const formattedCategory = category.replace(/\s+/g, '').toUpperCase();

                let base = (formattedName + formattedCategory).replace(/[^A-Z0-9]/g, '');
                base = base.slice(0, 4);

                const randomPart = Math.floor(10 + Math.random() * 90);
                const sku = (base + randomPart).substring(0, 6);
                skuInput.value = sku;
            }
        }

        categorySelect.addEventListener("change", generateSKU);
        nameInput.addEventListener("input", generateSKU);
        
        // Generate initial SKU if both fields have values
        if (categorySelect.value && nameInput.value) {
            generateSKU();
        }
    }
});
</script>


<!-- multiple Image -->
<script>
// Image Distribution Logic
document.addEventListener("DOMContentLoaded", function() {
    const imageInput = document.querySelector('input[name="image"]');
    const image2Input = document.querySelector('input[name="image2"]');
    const image3Input = document.querySelector('input[name="image3"]');
    const image4Input = document.querySelector('input[name="image4"]');
    const image5Input = document.querySelector('input[name="image5"]');
    const multipleImageInput = document.querySelector('input[name="multipleimage[]"]');
    const autoFillFlag = document.getElementById('auto_filled_from_multiple');

    // Helper to create a new FileList from an array of files using DataTransfer
    function createFileList(filesArray) {
        const dt = new DataTransfer();
        filesArray.forEach(file => dt.items.add(file));
        return dt.files;
    }

    // Distribute files: first 5 to individual inputs, rest to multipleimage
    function distributeFiles(files) {
        const fileArray = Array.from(files);
        const total = fileArray.length;

        // Assign individual image inputs (first 5)
        if (total >= 1) {
            imageInput.files = createFileList([fileArray[0]]);
        }
        if (total >= 2 && image2Input) {
            image2Input.files = createFileList([fileArray[1]]);
        }
        if (total >= 3 && image3Input) {
            image3Input.files = createFileList([fileArray[2]]);
        }
        if (total >= 4 && image4Input) {
            image4Input.files = createFileList([fileArray[3]]);
        }
        if (total >= 5 && image5Input) {
            image5Input.files = createFileList([fileArray[4]]);
        }

        // Remaining files (after index 4) go to multipleimage
        const remainingFiles = fileArray.slice(5);
        multipleImageInput.files = createFileList(remainingFiles);

        // Update the preview
        showDistributionPreview(fileArray, remainingFiles.length);

        // Set flag that auto-fill happened
        autoFillFlag.value = '1';
    }

    // Show preview of distribution
    function showDistributionPreview(allFiles, remainingCount) {
        let previewContainer = document.getElementById('distribution-preview');
        if (!previewContainer) {
            previewContainer = document.createElement('div');
            previewContainer.id = 'distribution-preview';
            previewContainer.className = 'alert alert-info mt-3';
            multipleImageInput.parentNode.appendChild(previewContainer);
        }

        let html = '<strong>Image Distribution:</strong><br>';
        if (allFiles.length >= 1) {
            html += `✓ <strong>Image 1</strong>: ${allFiles[0].name}<br>`;
        }
        if (allFiles.length >= 2) {
            html += `✓ <strong>Image 2</strong>: ${allFiles[1].name}<br>`;
        }
        if (allFiles.length >= 3) {
            html += `✓ <strong>Image 3</strong>: ${allFiles[2].name}<br>`;
        }
        if (allFiles.length >= 4) {
            html += `✓ <strong>Image 4</strong>: ${allFiles[3].name}<br>`;
        }
        if (allFiles.length >= 5) {
            html += `✓ <strong>Image 5</strong>: ${allFiles[4].name}<br>`;
        }
        if (remainingCount > 0) {
            html += `✓ <strong>Multiple Images Gallery</strong>: ${remainingCount} image(s)<br>`;
        } else {
            html += `✓ <strong>Multiple Images Gallery</strong>: none<br>`;
        }

        previewContainer.innerHTML = html;
    }

    // Clear all individual image inputs
    function clearIndividualInputs() {
        if (imageInput) imageInput.value = '';
        if (image2Input) image2Input.value = '';
        if (image3Input) image3Input.value = '';
        if (image4Input) image4Input.value = '';
        if (image5Input) image5Input.value = '';
    }

    // Remove preview
    function removePreview() {
        const preview = document.getElementById('distribution-preview');
        if (preview) preview.remove();
    }

    // --- Event Listeners ---

    // When multiple images are selected
    multipleImageInput.addEventListener('change', function(e) {
        const files = e.target.files;
        if (files.length > 0) {
            // Distribute the files
            distributeFiles(files);
        } else {
            // If user cleared the multiple input, also clear individuals? (optional)
            // For consistency, we clear individuals and flag
            clearIndividualInputs();
            autoFillFlag.value = '0';
            removePreview();
        }
    });

    // When any individual image input is manually changed, clear multipleimage and preview
    [imageInput, image2Input, image3Input, image4Input, image5Input].forEach(input => {
        if (!input) return;
        input.addEventListener('change', function() {
            // If this change was not triggered by our auto-fill, reset multipleimage
            // We can check a flag or simply clear multipleimage when any individual is manually changed.
            // But we must avoid clearing multipleimage when it was just auto-filled.
            // We'll use a small timeout to let the auto-fill complete.
            setTimeout(() => {
                // If multipleimage still has files, but we're manually changing an individual,
                // it means user wants to override. So clear multipleimage and preview.
                if (multipleImageInput.files.length > 0) {
                    multipleImageInput.value = ''; // clear
                    autoFillFlag.value = '0';
                    removePreview();

                    // Also clear other individuals? Probably not, but we can.
                    // We'll just let the user manage.
                }
            }, 10);
        });
    });

    // When form submits, remove preview to avoid confusion on refresh
    document.querySelector('form').addEventListener('submit', function() {
        removePreview();
    });
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

    freeShipping.addEventListener('change', toggleShippingField);
    costShipping.addEventListener('change', toggleShippingField);
});
</script>
@endsection