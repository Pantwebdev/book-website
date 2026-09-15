@extends('customer.layouts.app')

@section('content')
<div class="container py-4">

    <h4 class="mb-3">Buy Again</h4>


    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="ordersTable">
            <thead class="table-dark">
                <tr>
                    <th>Product</th>
                    <th>Image</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cartItems as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>
                            <img src="{{ asset('userassets/image/product/' . $item->product->image) }}" alt="{{ $item->product->name }}" width="50">
                        <td>{{ $item->qty }}</td>
                        <td>{{ $item->price }}</td>
                        <td><a href="{{ url('product/' . $item->product->slug) }}">View Details</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

