@include('userheader')

<div class="container mt-5">
    <div class="card p-4">
        <h4>Remaining Payment</h4>

        <p><b>Order No:</b> {{ $order->order_number }}</p>
        <p><b>Remaining:</b> ₹{{ $order->remaining_amount }}</p>

        <button id="payBtn" class="btn btn-success">Pay Now</button>
    </div>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
document.getElementById('payBtn').onclick = function () {

    fetch('/create-remaining-order/{{ $order->order_number }}')
    .then(res => res.json())
    .then(data => {

        var options = {
            key: data.key,
            amount: data.amount,
            currency: "INR",
            order_id: data.order_id, // ✅ IMPORTANT
            name: "Print Payment",
            description: "Remaining Payment",

            handler: function (response) {

                let form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('remaining.payment.success', $order->order_number) }}";

                form.innerHTML = `
                    @csrf
                    <input type="hidden" name="razorpay_payment_id" value="${response.razorpay_payment_id}">
                    <input type="hidden" name="razorpay_order_id" value="${response.razorpay_order_id}">
                    <input type="hidden" name="razorpay_signature" value="${response.razorpay_signature}">
                `;

                document.body.appendChild(form);
                form.submit();
            }
        };

        var rzp = new Razorpay(options);
        rzp.open();
    })
    .catch(err => {
        alert("Error creating payment order");
        console.error(err);
    });
}
</script>
<!-- <script>
document.getElementById('payBtn').onclick = function () {

    var options = {
        "key": "{{ env('RAZORPAY_KEY') }}",
        "amount": "{{ $order->remaining_amount * 100 }}",
        "currency": "INR",
        "name": "Print Payment",
        "description": "Remaining Payment",
        "handler": function (response) {

            let form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('remaining.payment.success', $order->order_number) }}";

            form.innerHTML = `
                @csrf
                <input type="hidden" name="razorpay_payment_id" value="${response.razorpay_payment_id}">
                <input type="hidden" name="razorpay_order_id" value="${response.razorpay_order_id}">
                <input type="hidden" name="razorpay_signature" value="${response.razorpay_signature}">
            `;

            document.body.appendChild(form);
            form.submit();
        }
    };

    var rzp = new Razorpay(options);
    rzp.open();
}
</script> -->

@include('userfooter')