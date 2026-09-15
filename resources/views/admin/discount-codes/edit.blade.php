@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Edit Discount Code: {{ $discountCode->code }}</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="{{ route('dashboard') }}"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('discount-codes.index') }}">Discount Codes</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="#">Edit Discount Code</a></li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Edit Discount Code</div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('discount-codes.update', $discountCode->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Discount Code *</label>
                                        <input type="text" name="code" class="form-control"
                                            value="{{ old('code', $discountCode->code) }}" required>
                                        @error('code')<small class="text-danger">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Type *</label>
                                        <select name="type" class="form-control" required>
                                            <option value="fixed"
                                                {{ old('type', $discountCode->type) == 'fixed' ? 'selected' : '' }}>
                                                Fixed Amount</option>
                                            <option value="percentage"
                                                {{ old('type', $discountCode->type) == 'percentage' ? 'selected' : '' }}>
                                                Percentage</option>
                                        </select>
                                        @error('type')<small class="text-danger">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Discount Value *</label>
                                        <input type="number" name="discount_value" class="form-control"
                                            value="{{ old('discount_value', $discountCode->discount_value) }}"
                                            step="0.01" min="0" required>
                                        @error('discount_value')<small
                                            class="text-danger">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Valid From *</label>
                                        <input type="date" name="valid_from" class="form-control"
                                            value="{{ old('valid_from', $discountCode->valid_from->format('Y-m-d')) }}"
                                            required>
                                        @error('valid_from')<small class="text-danger">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Valid Until *</label>
                                        <input type="date" name="valid_until" class="form-control"
                                            value="{{ old('valid_until', $discountCode->valid_until->format('Y-m-d')) }}"
                                            required>
                                        @error('valid_until')<small class="text-danger">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Usage Limit</label>
                                        <input type="number" name="usage_limit" class="form-control"
                                            value="{{ old('usage_limit', $discountCode->usage_limit) }}"
                                            placeholder="Unlimited if empty">
                                        @error('usage_limit')<small class="text-danger">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                            </div>

                            

                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="form-check">
                                        <input type="checkbox" name="is_active" class="form-check-input" id="is_active"
                                            value="1" {{ old('is_active', $discountCode->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active Discount Code
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Discount Code
                                </button>
                                <a href="{{ route('discount-codes.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Back to List
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Current Status Card -->
                <div class="card mt-4">
                    <div class="card-header">
                        <div class="card-title">Discount Code Status</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Current Usage:</strong>
                                <span class="badge badge-info">
                                    {{ $discountCode->used_count }}
                                    @if($discountCode->usage_limit)
                                    / {{ $discountCode->usage_limit }}
                                    @else
                                    / ∞
                                    @endif
                                </span>
                            </div>
                            <div class="col-md-3">
                                <strong>Status:</strong>
                                <span class="badge badge-{{ $discountCode->is_active ? 'success' : 'danger' }}">
                                    {{ $discountCode->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                           
                            <div class="col-md-3">
                                <strong>Validity:</strong>
                                <span
                                    class="badge badge-{{ $discountCode->valid_until->isFuture() ? 'success' : 'danger' }}">
                                    {{ $discountCode->valid_until->isFuture() ? 'Valid' : 'Expired' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Associated Products -->
               <!-- @php
$sampleProducts = \App\Models\Product::take(5)->get();
@endphp

<div class="card mt-4">
    <div class="card-header">
        <div class="card-title">Example Discount Preview (First 5 Products)</div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>SKU</th>
                        <th>Current Price</th>
                        <th>Discounted Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sampleProducts as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->sku }}</td>
                        <td>₹{{ $product->display_price ?? $product->mrp_price }}</td>
                        <td class="text-success">
                            ₹{{ calculateDiscountedPrice($product, $discountCode) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div> -->

            </div>
        </div>
    </div>
</div>

<style>
.badge {
    font-size: 0.9em;
    padding: 5px 10px;
}
</style>
@endsection

@php
// Helper function to calculate discounted price (you can also create a helper file)
function calculateDiscountedPrice($product, $discountCode) {
$price = $product->display_price ?? $product->mrp_price;
if ($discountCode->type === 'fixed') {
return max(0, $price - $discountCode->discount_value);
} else {
return $price * (1 - ($discountCode->discount_value / 100));
}
}
@endphp