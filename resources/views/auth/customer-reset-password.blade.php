@include('userheader')

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow" style="border: none; border-radius: 15px;">
                <div class="card-header text-center py-4" style="background: #f8f9fa; border-radius: 15px 15px 0 0;">
                    <h3 class="mb-0" style="color: #333; font-weight: 600;">
                        <i class="fa fa-lock me-2"></i> Reset Password
                    </h3>
                </div>
                
                <div class="card-body p-5">
                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <div class="alert alert-warning mb-4" id="timerAlert" style="border-radius: 10px;">
                        <i class="fa fa-clock me-2"></i>
                        <strong>Time-sensitive:</strong> This reset link expires in 
                        <span id="timeRemaining" style="color: #dc3545; font-weight: bold;"></span>
                    </div>

                    <form method="POST" action="{{ route('customer.password.update') }}" id="resetPasswordForm">
                        @csrf
                        
                        <input type="hidden" name="token" value="{{ $token }}">
                        <input type="hidden" name="email" value="{{ $email }}">
                        
                        <!-- Add this hidden field for return URL -->
                        @if(isset($return_url) && $return_url)
                        <input type="hidden" name="return_url" value="{{ $return_url }}">
                        @endif
                        
                        <div class="mb-4 position-relative">
                            <label for="password" class="form-label" style="font-weight: 500; color: #555;">
                                <i class="fa fa-key me-2"></i> New Password
                            </label>
                            <input type="password" class="form-control py-3 @error('password') is-invalid @enderror" 
                                   id="password" name="password" 
                                   placeholder="Enter new password" required
                                   style="border-radius: 10px; border: 1px solid #ddd; font-size: 16px;">
                                   
                                     <span class="togglePassword position-absolute top-45 translate-middle-y me-3" 
                                       style="cursor:pointer; right: 10px;">
                                       <i class="fa fa-eye"></i>
                                      </span>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted mt-2">
                                <i class="fa fa-info-circle me-1"></i>
                                Must be at least 8 characters with uppercase, lowercase, number & special character
                            </small>
                        </div>

                        <div class="mb-4 position-relative">
                            <label for="password_confirmation" class="form-label" style="font-weight: 500; color: #555;">
                                <i class="fa fa-key me-2"></i> Confirm Password
                            </label>
                            <input type="password" class="form-control py-3" 
                                   id="password_confirmation" name="password_confirmation" 
                                   placeholder="Confirm new password" required
                                   style="border-radius: 10px; border: 1px solid #ddd; font-size: 16px;">
                                   
                                   <span class="togglePassword position-absolute maintop translate-middle-y me-3"
                                      style="cursor:pointer; right: 10px;">
                                    <i class="fa fa-eye"></i>
                                  </span>
                        </div>

                        <button type="submit" class="btn w-100 py-3" 
                                style="background: linear-gradient(135deg, #28a745, #218838); 
                                       color: white; border: none; border-radius: 10px; 
                                       font-weight: 600; font-size: 16px;"
                                id="submitBtn">
                            <i class="fa fa-check-circle me-2"></i> Reset Password
                        </button>
                        
                        <div class="text-center mt-4">
                            <a href="{{ route('customer.password.request') }}" class="text-decoration-none" 
                               style="color: #1b1a2a; font-weight: 500;">
                                <i class="fa fa-arrow-clockwise me-2"></i> Request New Reset Link
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
    border-color: #28a745;
    box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.25);
}

.btn:hover {
    background: linear-gradient(135deg, #218838, #1e7e34) !important;
    transform: translateY(-2px);
    transition: all 0.3s ease;
}

.alert-warning {
    background: #fff3cd;
    border: 1px solid #ffeaa7;
    color: #856404;
    border-radius: 10px;
}

.togglePassword {
    top: 50%;
    transform: translateY(-50%);
}

.maintop{
    top:67%;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set expiration time (2 minutes from creation)
    const expiresAt = new Date('{{ $expires_at->toIso8601String() }}');
    const timerAlert = document.getElementById('timerAlert');
    const timeRemaining = document.getElementById('timeRemaining');
    const form = document.getElementById('resetPasswordForm');
    const submitBtn = document.getElementById('submitBtn');
    let timerInterval;
    
    function updateTimer() {
        const now = new Date();
        const diffMs = expiresAt - now;
        
        if (diffMs <= 0) {
            // Time expired
            timerAlert.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fa fa-exclamation-triangle me-2"></i>
                    <strong>This reset link has expired!</strong> Please request a new password reset link.
                </div>
            `;
            
            // Disable form
            form.querySelectorAll('input, button').forEach(el => {
                el.disabled = true;
            });
            
            submitBtn.innerHTML = '<i class="fa fa-ban me-2"></i> Link Expired';
            submitBtn.style.background = 'linear-gradient(135deg, #6c757d, #5a6268)';
            
            clearInterval(timerInterval);
            
            // Redirect after 3 seconds
            setTimeout(() => {
                window.location.href = '{{ route("customer.password.request") }}';
            }, 3000);
            
            return;
        }
        
        const minutes = Math.floor(diffMs / 60000);
        const seconds = Math.floor((diffMs % 60000) / 1000);
        
        timeRemaining.textContent = `${minutes}m ${seconds}s`;
        
        // Change color when less than 30 seconds
        if (minutes === 0 && seconds < 30) {
            timeRemaining.style.color = '#dc3545';
            timerAlert.style.border = '2px solid #dc3545';
        }
    }
    
    // Start timer
    timerInterval = setInterval(updateTimer, 1000);
    updateTimer(); // Initial call
    
    // Form validation
    form.addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;
        
        // Check if link expired
        const now = new Date();
        if (expiresAt - now <= 0) {
            e.preventDefault();
            alert('This reset link has expired. Please request a new one.');
            return false;
        }
        
        // Check password match
        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Passwords do not match. Please try again.');
            return false;
        }
        
        // Password complexity validation
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        if (!passwordRegex.test(password)) {
            e.preventDefault();
            alert('Password must contain at least 8 characters, one uppercase letter, one lowercase letter, one number and one special character.');
            return false;
        }
        
        // Show loading
        submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i> Resetting...';
        submitBtn.disabled = true;
        
        return true;
    });
    
    // Show password strength
    document.getElementById('password').addEventListener('input', function() {
        const password = this.value;
        const strengthText = document.querySelector('.form-text');
        
        if (password.length > 0) {
            const hasLower = /[a-z]/.test(password);
            const hasUpper = /[A-Z]/.test(password);
            const hasNumber = /\d/.test(password);
            const hasSpecial = /[@$!%*?&]/.test(password);
            const isLong = password.length >= 8;
            
            const requirements = [];
            if (!hasLower) requirements.push('lowercase letter');
            if (!hasUpper) requirements.push('uppercase letter');
            if (!hasNumber) requirements.push('number');
            if (!hasSpecial) requirements.push('special character');
            if (!isLong) requirements.push('8+ characters');
            
            if (requirements.length > 0) {
                strengthText.innerHTML = `<i class="fa fa-times-circle me-1" style="color:#dc3545"></i> Missing: ${requirements.join(', ')}`;
                strengthText.style.color = '#dc3545';
            } else {
                strengthText.innerHTML = `<i class="fa fa-check-circle me-1" style="color:#28a745"></i> Password strength: Strong ✓`;
                strengthText.style.color = '#28a745';
            }
        } else {
            strengthText.innerHTML = `<i class="fa fa-info-circle me-1"></i> Must be at least 8 characters with uppercase, lowercase, number & special character`;
            strengthText.style.color = '#6c757d';
        }
    });
});
</script>


<!-- password hide or show karna ke liye js -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
    const toggles = document.querySelectorAll(".togglePassword");

    toggles.forEach(toggle => {
        const input = toggle.parentElement.querySelector("input");
        const icon = toggle.querySelector("i");

        toggle.addEventListener("click", function() {
            if (input.type === "password") {
                input.type = "text";
                icon.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.replace("fa-eye-slash", "fa-eye");
            }
        });
    });
});
</script>

@include('userfooter')