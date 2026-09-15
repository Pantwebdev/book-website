@extends('layouts.app')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0">
                <div class="card-header bg-gradient-primary text-white">
                    <h4 class="mb-0">Reset Password</h4>
                </div>
                
                <div class="card-body p-4">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <p class="text-muted mb-4">
                        Enter your email address and we'll send you a link to reset your password.
                    </p>

                    <form method="POST" action="{{ route('customer.password.email') }}" id="forgotPasswordForm">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input id="email" type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autocomplete="email" 
                                   autofocus>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div id="forgotPasswordMessage" class="mb-3" style="display:none;"></div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                <span class="spinner-border spinner-border-sm me-2" id="forgotSpinner" style="display:none;"></span>
                                Send Password Reset Link
                            </button>
                            
                            <a href="{{ route('customer.login') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Back to Login
                            </a>
                        </div>
                    </form>
                </div>
                
                <div class="card-footer text-center bg-light">
                    <p class="mb-0">
                        Remember your password? 
                        <a href="{{ route('customer.login') }}">Login here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #d4a574 0%, #b08d57 100%) !important;
    }
    .card {
        border-radius: 12px;
        overflow: hidden;
    }
    .form-control:focus {
        border-color: #d4a574;
        box-shadow: 0 0 0 0.25rem rgba(212, 165, 116, 0.25);
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('forgotPasswordForm');
    const submitBtn = document.getElementById('submitBtn');
    const forgotSpinner = document.getElementById('forgotSpinner');
    const messageDiv = document.getElementById('forgotPasswordMessage');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Reset message
        messageDiv.style.display = 'none';
        messageDiv.innerHTML = '';
        
        // Show loading
        submitBtn.disabled = true;
        forgotSpinner.style.display = 'inline-block';
        
        const formData = new FormData(this);
        
        fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageDiv.style.display = 'block';
                messageDiv.innerHTML = `
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        ${data.message}
                    </div>
                `;
                
                // Clear form
                form.reset();
                
                // Show additional instructions
                setTimeout(() => {
                    messageDiv.innerHTML += `
                        <div class="alert alert-info mt-3">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            Please check your email inbox (and spam folder) for the reset link.
                            The link will expire in 60 minutes.
                        </div>
                    `;
                }, 500);
            } else {
                messageDiv.style.display = 'block';
                if (data.errors) {
                    let errors = '';
                    Object.values(data.errors).forEach(errorArray => {
                        errors += `<div>${errorArray.join('<br>')}</div>`;
                    });
                    messageDiv.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            ${errors}
                        </div>
                    `;
                } else {
                    messageDiv.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            ${data.message || 'Something went wrong. Please try again.'}
                        </div>
                    `;
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            messageDiv.style.display = 'block';
            messageDiv.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Network error. Please check your internet connection and try again.
                </div>
            `;
        })
        .finally(() => {
            submitBtn.disabled = false;
            forgotSpinner.style.display = 'none';
        });
    });
});
</script>
@endsection