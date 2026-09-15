<footer class="bg-light text-center py-3 mt-5">
    <small>© {{ date('Y') }} Ajhuie books shop. All Rights Reserved.</small>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


<div class="modal fade" id="trackOrderModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Track Your Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('customer.order.track.submit') }}" method="POST">
                @csrf

                <div class="modal-body">

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Order Number</label>
                        <input type="text"
                               name="order_number"
                               class="form-control"
                               placeholder="ORD123456"
                               required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary w-100">
                        Track Order
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
