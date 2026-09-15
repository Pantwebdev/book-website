@include('userheader')

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Print Order Confirmation</h4>
                </div>
                <div class="card-body">
                    <h5>Thank you for your order!</h5>
                    <p>Your print order has been placed successfully.</p>

                    <table class="table">
                        <tr>
                            <th>Order ID:</th>
                            <td>{{ $printOrder->order_number }}</td>
                        </tr>
                        <tr>
                            <th>Name:</th>
                            <td>{{ $printOrder->name }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $printOrder->email }}</td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td>{{ $printOrder->phone }}</td>
                        </tr>
                        <tr>
                            <th>Print Type:</th>
                            <td>{{ ucfirst($printOrder->print_type) }}</td>
                        </tr>
                        <tr>
                            <th>Pages:</th>
                            <td>{{ $printOrder->pages }}</td>
                        </tr>
                        <tr>
                            <th>Copies:</th>
                            <td>{{ $printOrder->copies }}</td>
                        </tr>
                        <tr>
                            <th>Total Amount:</th>
                            <td>₹{{ number_format($printOrder->total_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Paid Amount (50%):</th>
                            <td>₹{{ number_format($printOrder->paid_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Payment Status:</th>
                            <td>
                                @if($printOrder->payment_status == 'paid')
                                    <span class="badge bg-success">Paid</span>
                                @elseif($printOrder->payment_status == 'partial')
                                    <span class="badge bg-warning">Pending₹{{ number_format($printOrder->remaining_amount, 2) }}</span>
                                @else
                                    <span class="badge bg-danger">Failed</span>
                                @endif
                            </td>
                        </tr>
                    </table>

                    <a href="{{ route('print.upload') }}" class="btn btn-primary">Upload Another</a>
                    <a href="{{ route('index') }}" class="btn btn-secondary">Go to Home</a>
                </div>
            </div>
        </div>
    </div>
</div>

@include('userfooter')