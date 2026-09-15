@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Discount Codes</h3>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Create New Discount Code</div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('discount-codes.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Discount Code *</label>
                                        <input type="text" name="code" class="form-control" placeholder="e.g., AHU19"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Type *</label>
                                        <select name="type" class="form-control" required>
                                            <option value="fixed">Fixed Amount</option>
                                            <option value="percentage">Percentage</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Discount Value *</label>
                                        <input type="number" name="discount_value" class="form-control"
                                            placeholder="e.g., 100" step="0.01" min="0" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Valid From *</label>
                                        <input type="date" name="valid_from" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Valid Until *</label>
                                        <input type="date" name="valid_until" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Usage Limit</label>
                                        <input type="number" name="usage_limit" class="form-control"
                                            placeholder="Unlimited if empty">
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Applicable Products *</label>
                                        <select name="product_ids[]" class="form-control" multiple required
                                            style="height: 150px;">
                                            @foreach($products as $product)
                                            <option value="{{ $product->id }}">
                                                {{ $product->name }} (SKU: {{ $product->sku }})
                                            </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Hold Ctrl to select multiple products</small>
                                    </div>
                                </div>
                            </div> -->

                            <div class="form-group mt-3">
                                <button type="submit" class="btn btn-primary">Create Discount Code</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <div class="card-title">All Discount Codes</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Type</th>
                                        <th>Value</th>
                                        <th>Valid Period</th>
                                        <th>Usage</th>
                                        <th>Products</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($discountCodes as $code)
                                    <tr>
                                        <td><strong>{{ $code->code }}</strong></td>
                                        <td>
                                            <span
                                                class="badge badge-{{ $code->type == 'fixed' ? 'primary' : 'success' }}">
                                                {{ ucfirst($code->type) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($code->type == 'fixed')
                                            ₹{{ $code->discount_value }}
                                            @else
                                            {{ $code->discount_value }}%
                                            @endif
                                        </td>
                                        <td>
                                            {{ $code->valid_from->format('M d, Y') }} -
                                            {{ $code->valid_until->format('M d, Y') }}
                                        </td>
                                        <td>
                                            {{ $code->used_count }}
                                            @if($code->usage_limit)
                                            / {{ $code->usage_limit }}
                                            @else
                                            / ∞
                                            @endif
                                        </td>
                                        <td>
    <span class="badge badge-info">
        All Products
    </span>
</td>

                                        <td>
                                            <span class="badge badge-{{ $code->is_active ? 'success' : 'danger' }}">
                                                {{ $code->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('discount-codes.edit', $code->id) }}"
                                                class="btn btn-link btn-primary btn-lg" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <form action="{{ route('discount-codes.destroy', $code->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf @method('DELETE')

                                                <button class="btn btn-link btn-danger" type="submit"
                                                    onclick="return confirm('Are you sure to delete?')" title="Delete">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modals -->

@endsection