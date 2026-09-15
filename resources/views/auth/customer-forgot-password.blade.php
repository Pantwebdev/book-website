@include('userheader')

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow" style="border: none; border-radius: 15px;">
                <div class="card-header text-center py-4" style="background: #f8f9fa; border-radius: 15px 15px 0 0;">
                    <h3 class="mb-0" style="color: #333; font-weight: 600;">
                        <i class="fa fa-key me-2"></i> Forgot Password
                    </h3>
                </div>
                
                <div class="card-body p-5">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('customer.password.email') }}" id="forgotPasswordForm">
                        @csrf
                        
                        <!-- Check if return_url is in session and pass it -->
                        @if(session('password_reset_return_url'))
                        <input type="hidden" name="return_url" value="{{ session('password_reset_return_url') }}">
                        @endif
                        
                        <div class="mb-4">
                            <label for="email" class="form-label" style="font-weight: 500; color: #555;">
                                <i class="fa fa-envelope me-2"></i>Email Address
                            </label>
                            <input type="email" class="form-control py-3 @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" 
                                   placeholder="Enter your registered email" required
                                   style="border-radius: 10px; border: 1px solid #ddd; font-size: 16px;">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info mb-4" style="border-radius: 10px; border: none; background: #e7f3ff;">
                            <i class="bi bi-info-circle-fill me-2"></i>
                           <strong>Note:</strong> Reset link valid for <strong style="color: #dc3545;">4 minutes</strong>. Check your email immediately.


                        <button type="submit" class="btn w-100 py-3" 
                                style="background: linear-gradient(135deg, #cb7000, #a95e02);
                                       color: white; border: none; border-radius: 10px; 
                                       font-weight: 600; font-size: 16px;"
                                id="submitBtn">
                            <i class="fa fa-paper-plane me-2"></i> Send Password Reset Link
                        </button>
                        
                        <div class="text-center mt-4">
                            <a href="javascript:void(0)" onclick="openLoginModal()" class="text-decoration-none" 
                               style="color: #1b1a2a; font-weight: 500;">
                                <i class="fa fa-arrow-left me-2"></i> Back to Login
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
body {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
}

.card {
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
}

.form-control:focus {
    border-color: #e40046;
    box-shadow: 0 0 0 0.25rem rgba(228, 0, 70, 0.25);
}

.btn:hover {
   background-color:#181724 !important;
    transform: translateY(-2px);
    transition: all 0.3s ease;
}

.alert-success {
    background: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
    border-radius: 10px;
}

.alert-danger {
    background: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
    border-radius: 10px;
}

.alert-info {
    background: #e7f3ff;
    border: 1px solid #b3d7ff;
    color: #004085;
    border-radius: 10px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('forgotPasswordForm');
    const submitBtn = document.getElementById('submitBtn');
    
    form.addEventListener('submit', function(e) {
        const email = document.getElementById('email').value.trim();
        
        if (!email) {
            e.preventDefault();
            alert('Please enter your email address.');
            return;
        }
        
        // Email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            e.preventDefault();
            alert('Please enter a valid email address.');
            return;
        }
        
        // Show loading
        submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i> Sending...';
        submitBtn.disabled = true;
        
        // Allow form submission
        return true;
    });
});

function openLoginModal() {
    // Go back to home page and open login modal
    window.location.href = '{{ url("/") }}?open_login=1';
}
</script>

@include('userfooter')