@include('userheader')

<div class="container mt-4" style="background-image: url('{{ url('userassets/image/footerbg.webp') }}');">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card  border-0" style="background-image: url('{{ url('userassets/image/footerbg.webp') }}');">

                <div class="card-body">

                    @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form id="payNowForm" enctype="multipart/form-data" novalidate>
                        @csrf
                        <input type="hidden" name="print_type" value="black" id="printTypePay">
                        <input type="hidden" name="paper_size" value="A4">

                        <div class="mb-3">

                            <div class="upload-trend">

                                <input type="file" name="file" id="pdfFilePay" accept="application/pdf" hidden>

                                <div class="upload-card-trend" onclick="document.getElementById('pdfFilePay').click()">

                                    <img src="{{url('userassets/image/uplodedoc.png')}}" class="img-fluid"
                                        alt="upload file">


                                    <div id="fileName" class="file-chip"></div>
                                    <h4>Upload your document</h4>

                                </div>

                                <div id="pageCountPay" class="page-count"></div>

                            </div>




                            <div class="mt-2 text-success fw-bold" id="pageCountPay"></div>
                            <div class="invalid-feedback" id="fileError"></div>
                        </div>

                        <div class="mb-5">
                            <label class="form-label" style="font-size: 20px;
                                font-family: math;
                                letter-spacing: 1px;
                                font-weight: 600;
                                border-bottom: 1px solid gray;
                                margin-bottom: 18px;">Print Type</label>
                            <div>
                                <button type="button" class="btn coloractive print-type-btn-pay active"
                                    data-mode="black" data-rate="0.60">Black & White (₹0.60/page)</button>
                                <button type="button" class="btn coloractive print-type-btn-pay" data-mode="color"
                                    data-rate="3.00">Color (₹3/page)</button>
                            </div>
                        </div>



                        <div class="border-manul">
                            <div class="row g-3 ">
                                <h5 style="font-size: 25px; font-family: 'Poppins';">Your Details</h5>

                                <div class="dividerswction" style="margin:0px">
                                   
                                            <img src="{{url('userassets/image/flowericon-img.png')}}" class="decorative icon"
                                        alt="upload file">
                                </div>


                                <div class="col-md-6">
                                    <label>Full Name *</label>
                                    <input type="text" name="name" class="form-control"
                                        value="{{ $customer->name ?? '' }}" id="nameField" required>
                                    <div class="invalid-feedback" id="nameError"></div>
                                </div>
                                <div class="col-md-6">
                                    <label>Email *</label>
                                    <input type="email" name="email" class="form-control"
                                        value="{{ $customer->email ?? '' }}" id="emailField" required>
                                    <div class="invalid-feedback" id="emailError"></div>
                                </div>
                                <div class="col-md-6">
                                    <label>Phone *</label>
                                    <input type="tel" name="phone" class="form-control"
                                        value="{{ $customer->phone ?? '' }}" maxlength="10" id="phoneField" required>
                                    <div class="invalid-feedback" id="phoneError"></div>
                                </div>

                                <div class="col-md-6">
                                    <label>Total Copy *</label>
                                    <input type="number" name="copies" class="form-control" value="1" min="1"
                                        id="copiesPay" required>
                                    <div class="invalid-feedback" id="copiesError"></div>
                                </div>
                            </div>

                            <div class="mb-3 mt-3" id="totalPricePay">Total: ₹0.00</div>
                            <div class="mb-3" id="payAmount">You Pay (50%): ₹0.00</div>

                            <!-- General error message -->
                            <div class="alert alert-danger d-none" id="generalError"></div>

                            <button type="button" class="btn proccesbtn w-100" id="payNowBtn" disabled>Proceed to Pay
                                50%</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Razorpay script -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.6.347/pdf.min.js"></script>
<!-- NEW CODE -->
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

fetch('{{ route("print.initiate.payment") }}', {
    method: 'POST',
    body: formData,
    headers: {
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json'
    }
})</script>
<!-- NEW CODE -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ========== PAY TAB FUNCTIONALITY ==========
    const payTypeBtns = document.querySelectorAll('.print-type-btn-pay');
    const payPrintTypeInput = document.getElementById('printTypePay');
    const payCopies = document.getElementById('copiesPay');
    const payTotalSpan = document.getElementById('totalPricePay');
    const payAmountSpan = document.getElementById('payAmount');
    const payNowBtn = document.getElementById('payNowBtn');
    let payPages = 0;
    let payPricePerPage = 0.60;

    // Handle print type selection
    payTypeBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            payTypeBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            payPrintTypeInput.value = this.dataset.mode;
            payPricePerPage = parseFloat(this.dataset.rate);
            updatePayTotal();
        });
    });

    // Handle file selection - get page count
    const payFileInput = document.getElementById('pdfFilePay');
    const pageCountPay = document.getElementById('pageCountPay');

    payFileInput.addEventListener('change', function(e) {
        let file = e.target.files[0];
        if (file && file.type === 'application/pdf') {
            let reader = new FileReader();
            reader.onload = function() {
                let typedarray = new Uint8Array(this.result);
                pdfjsLib.getDocument(typedarray).promise.then(function(pdf) {
                    payPages = pdf.numPages;
                    pageCountPay.innerHTML = 'Total Pages: ' + payPages;
                    updatePayTotal();
                });
            };
            reader.readAsArrayBuffer(file);
        } else {
            payPages = 0;
            pageCountPay.innerHTML = '';
            updatePayTotal();
        }
    });

    // function updatePayTotal() {
    //     let copies = parseInt(payCopies.value) || 1;
    //     let total = payPages * copies * payPricePerPage;
    //     let payAmount = total / 2;
    //     payTotalSpan.textContent = 'Total: ₹' + total.toFixed(2);
    //     payAmountSpan.textContent = 'You Pay (50%): ₹' + payAmount.toFixed(2);

    //     // Enable button only if we have pages and valid total
    //     payNowBtn.disabled = (payPages === 0 || total <= 0);
    // }
   // 50 copy code Start
    function updatePayTotal() {
        let copies = parseInt(payCopies.value) || 1;
        let total = payPages * copies * payPricePerPage;
        let payAmount = total / 2;
        payTotalSpan.textContent = 'Total: ₹' + total.toFixed(2);
        payAmountSpan.textContent = 'You Pay (50%): ₹' + payAmount.toFixed(2);

        // ✅ Minimum 50 pages check
        if (payPages > 0 && payPages < 50) {
            document.getElementById('pageCountPay').innerHTML = 
                'Total Pages: ' + payPages + 
                ' <span style="color:red;">❌ Minimum 50 pages required.</span>';
            payNowBtn.disabled = true;
        } else if (payPages >= 50) {
            document.getElementById('pageCountPay').innerHTML = 
                'Total Pages: ' + payPages + 
                ' <span style="color:green;">✅</span>';
            payNowBtn.disabled = (total <= 0);
        } else {
            document.getElementById('pageCountPay').innerHTML = '';
            payNowBtn.disabled = true;
        }
    }
   // 50 copy code end
    payCopies.addEventListener('input', updatePayTotal);

    // ========== VALIDATION FUNCTIONS ==========
    function clearFieldError(fieldId) {
        const field = document.getElementById(fieldId);
        if (field) {
            field.classList.remove('is-invalid');
        }
        const errorEl = document.getElementById(fieldId + 'Error');
        if (errorEl) {
            errorEl.textContent = '';
        }
    }

    function showFieldError(fieldId, message) {
        const field = document.getElementById(fieldId);
        if (field) {
            field.classList.add('is-invalid');
        }
        const errorEl = document.getElementById(fieldId + 'Error');
        if (errorEl) {
            errorEl.textContent = message;
        }
    }

    function clearAllErrors() {
        clearFieldError('nameField');
        clearFieldError('emailField');
        clearFieldError('phoneField');
        clearFieldError('pdfFilePay');
        clearFieldError('copiesPay');
        document.getElementById('generalError').classList.add('d-none');
    }

    function validateForm() {
        let isValid = true;
        clearAllErrors();

        // Name
        const name = document.getElementById('nameField').value.trim();
        if (!name) {
            showFieldError('nameField', 'Full name is required.');
            isValid = false;
        }

        // Email
        const email = document.getElementById('emailField').value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!email) {
            showFieldError('emailField', 'Email is required.');
            isValid = false;
        } else if (!emailRegex.test(email)) {
            showFieldError('emailField', 'Please enter a valid email address.');
            isValid = false;
        }

        // Phone
        const phone = document.getElementById('phoneField').value.replace(/\D/g, '');
        if (!phone) {
            showFieldError('phoneField', 'Phone number is required.');
            isValid = false;
        } else if (phone.length !== 10) {
            showFieldError('phoneField', 'Please enter a valid 10-digit phone number.');
            isValid = false;
        }

        // File
        const file = payFileInput.files[0];
        if (!file) {
            showFieldError('pdfFilePay', 'Please select a PDF file.');
            isValid = false;
        } else if (file.type !== 'application/pdf') {
            showFieldError('pdfFilePay', 'Only PDF files are allowed.');
            isValid = false;
        } else if (file.size > 20000000) { // 20MB
            showFieldError('pdfFilePay', 'File size must be less than 20MB.');
            isValid = false;
        }

        // Copies
        const copies = parseInt(payCopies.value);
        if (copies < 1 || isNaN(copies)) {
            showFieldError('copiesPay', 'Number of copies must be at least 1.');
            isValid = false;
        }

        return isValid;
    }

    // Real-time validation clearing
    document.getElementById('nameField').addEventListener('input', () => clearFieldError('nameField'));
    document.getElementById('emailField').addEventListener('input', () => clearFieldError('emailField'));
    document.getElementById('phoneField').addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '').slice(0, 10);
        clearFieldError('phoneField');
    });
    document.getElementById('pdfFilePay').addEventListener('change', () => clearFieldError('pdfFilePay'));
    document.getElementById('copiesPay').addEventListener('input', () => clearFieldError('copiesPay'));

    // ========== PAYMENT INITIATION ==========
    payNowBtn.addEventListener('click', function() {
        if (!validateForm()) {
            return;
        }

        let form = document.getElementById('payNowForm');
        let formData = new FormData(form);

        payNowBtn.disabled = true;
        payNowBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Processing...';
        document.getElementById('generalError').classList.add('d-none');

        fetch('{{ route("print.initiate.payment") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Open Razorpay
                    let options = {
                        key: data.razorpay_key,
                        amount: data.amount,
                        currency: 'INR',
                        name: '{{ env("APP_NAME") }}',
                        description: 'Print Order #' + data.order_id,
                        order_id: data.razorpay_order_id,
                        handler: function(response) {
                            // Redirect to success page with payment details
                            let redirectUrl =
                                '{{ route("print.payment.success", ":order_id") }}'.replace(
                                    ':order_id', data.order_id);
                            let form = document.createElement('form');
                            form.method = 'POST';
                            form.action = redirectUrl;
                            form.innerHTML = `
                            @csrf
                            <input type="hidden" name="razorpay_payment_id" value="${response.razorpay_payment_id}">
                            <input type="hidden" name="razorpay_order_id" value="${response.razorpay_order_id}">
                            <input type="hidden" name="razorpay_signature" value="${response.razorpay_signature}">
                        `;
                            document.body.appendChild(form);
                            form.submit();
                        },
                        prefill: {
                            name: data.customer_name,
                            email: data.customer_email,
                            contact: data.customer_phone
                        },
                        modal: {
                            ondismiss: function() {
                                payNowBtn.disabled = false;
                                payNowBtn.innerHTML = 'Proceed to Pay 50%';
                            }
                        },
                        theme: {
                            color: '#28a745'
                        }
                    };
                    let rzp = new Razorpay(options);
                    rzp.open();
                } else {
                    // Show server-side validation errors
                    if (data.errors) {
                        // Loop through errors and display inline
                        for (let field in data.errors) {
                            if (field === 'file') {
                                showFieldError('pdfFilePay', data.errors[field][0]);
                            } else if (field === 'copies') {
                                showFieldError('copiesPay', data.errors[field][0]);
                            } else if (field === 'name') {
                                showFieldError('nameField', data.errors[field][0]);
                            } else if (field === 'email') {
                                showFieldError('emailField', data.errors[field][0]);
                            } else if (field === 'phone') {
                                showFieldError('phoneField', data.errors[field][0]);
                            } else {
                                // general error
                                document.getElementById('generalError').textContent = data.errors[
                                    field][0];
                                document.getElementById('generalError').classList.remove('d-none');
                            }
                        }
                    } else {
                        document.getElementById('generalError').textContent = data.message ||
                            'Error initiating payment';
                        document.getElementById('generalError').classList.remove('d-none');
                    }
                    payNowBtn.disabled = false;
                    payNowBtn.innerHTML = 'Proceed to Pay 50%';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('generalError').textContent =
                    'Something went wrong. Please try again.';
                document.getElementById('generalError').classList.remove('d-none');
                payNowBtn.disabled = false;
                payNowBtn.innerHTML = 'Proceed to Pay 50%';
            });
    });

    // Restrict phone input to digits
    document.querySelector('input[name="phone"]').addEventListener('input', function(e) {
        this.value = this.value.replace(/\D/g, '').slice(0, 10);
    });
});
</script>


<script>
document.getElementById("pdfFilePay").addEventListener("change", function() {
    if (this.files.length > 0) {
        document.getElementById("fileName").innerText =
            this.files[0].name;
    }
});
</script>




<style>
/* Error message styling - using Bootstrap's built-in behavior */
.invalid-feedback {
    font-size: 0.875em;
    color: #dc3545;
    margin-top: 0.25rem;
}

/* Ensure error messages are visible when field is invalid */
.is-invalid~.invalid-feedback {
    display: block !important;
}

.form-control.is-invalid {
    border-color: #dc3545;
    padding-right: calc(1.5em + 0.75rem);
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}



.proccesbtn {
    background-color: #151b32;
    color: #fff;
}

.coloractive {
    background-color: #df8a0b;
    color: #fff;
}

.coloractive.active {
    font-family: 'Poppins';
    background-color: #151b32;
    color: #fff;
    letter-spacing: 1px;
    transition: 1s;
}

.coloractive {
    font-family: 'Poppins';
    letter-spacing: 1px;
    transition: 1s;
}

.upload-illustration {
    width: 120px;
    margin-bottom: 15px;
    opacity: .9;
}


.border-manul {
    border: 1px solid #c1c1c1;
    padding: 20px;
    border-radius: 5px;
}

.border-manul label {
    font-family: math;
    font-size: 18px;
    letter-spacing: 1px;
    font-weight: 600;
    color: #343434;
    display: flex;
    justify-content: start;
}

.upload-trend {
    margin-top: 40px;
}

.upload-card-trend {
    cursor: pointer;
    transition: .35s;
    display: flex;
    flex-direction: column;
    align-items: center;
}






.file-chip {
    display: inline-block;
    background: #ecfdf5;
    color: #16a34a;
    font-size: 10px;
    font-weight: 600;
}

.upload-card-trend img {
    height: 80px;
}

.upload-card-trend img:hover {
    transform: translateY(-6px);
    transition: 0.5s;
}

.btn:hover{
    color: #fff;
}

@media (max-width: 512px) {
    .upload-trend {
        margin-top: 10px;
    }

    .coloractive.active {
        margin: 8px 0px;

    }

    .form-label {
        margin-bottom: unset !important;
    }


}
</style>

@include('userfooter')