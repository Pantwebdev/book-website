@extends('layouts.app')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0">
                <div class="card-header bg-gradient-primary text-white">
                    <h4 class="mb-0">Create New Password</h4>
                </div>
                
                <div class="card-body p-4">
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <p class="text-muted mb-4">
                        Please enter your new password below.
                    </p>

                    <form method="POST" action="{{ route('customer.password.update') }}" id="resetPasswordForm">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input id="email" type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   name="email" 
                                   value="{{ $email ?? old('email') }}" 
                                   required 
                                   autocomplete="email" 
                                   autofocus>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <div class="input-group">
                                <input id="password" type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       name="password" 
                                       required 
                                       autocomplete="new-password">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <small class="form-text text-muted">
                                Password must be at least 6 characters long.
                            </small>
                        </div>

                        <div class="form-group mb-4">
                            <label for="password-confirm" class="form-label">Confirm New Password</label>
                            <div class="input-group">
                                <input id="password-confirm" type="password" 
                                       class="form-control" 
                                       name="password_confirmation" 
                                       required 
                                       autocomplete="new-password">
                                <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div id="resetPasswordMessage" class="mb-3" style="display:none;"></div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                <span class="spinner-border spinner-border-sm me-2" id="resetSpinner" style="display:none;"></span>
                                Reset Password
                            </button>
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
    .password-strength {
        margin-top: 5px;
        font-size: 12px;
    }
    .strength-weak { color: #dc3545; }
    .strength-medium { color: #ffc107; }
    .strength-strong { color: #28a745; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('resetPasswordForm');
    const submitBtn = document.getElementById('submitBtn');
    const resetSpinner = document.getElementById('resetSpinner');
    const messageDiv = document.getElementById('resetPasswordMessage');
    const togglePasswordBtn = document.getElementById('togglePassword');
    const togglePasswordConfirmBtn = document.getElementById('togglePasswordConfirm');
    const passwordInput = document.getElementById('password');
    const passwordConfirmInput = document.getElementById('password-confirm');
    
    // Toggle password visibility
    togglePasswordBtn.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.querySelector('i').classList.toggle('bi-eye');
        this.querySelector('i').classList.toggle('bi-eye-slash');
    });
    
    togglePasswordConfirmBtn.addEventListener('click', function() {
        const type = passwordConfirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordConfirmInput.setAttribute('type', type);
        this.querySelector('i').classList.toggle('bi-eye');
        this.querySelector('i').classList.toggle('bi-eye-slash');
    });
    
    // Password strength indicator
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        let strength = '';
        let color = '';
        
        if (password.length === 0) {
            strength = '';
        } else if (password.length < 6) {
            strength = 'Weak (minimum 6 characters)';
            color = 'strength-weak';
        } else if (password.length < 8) {
            strength = 'Medium';
            color = 'strength-medium';
        } else if (/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/.test(password)) {
            strength = 'Strong';
            color = 'strength-strong';
        } else {
            strength = 'Medium (add uppercase, lowercase & numbers)';
            color = 'strength-medium';
        }
        
        // Remove existing indicator
        let indicator = this.parentNode.parentNode.querySelector('.password-strength');
        if (!indicator) {
            indicator = document.createElement('div');
            indicator.className = 'password-strength';
            this.parentNode.parentNode.appendChild(indicator);
        }
        
        if (strength) {
            indicator.innerHTML = `<span class="${color}">${strength}</span>`;
            indicator.style.display = 'block';
        } else {
            indicator.style.display = 'none';
        }
    });
    
    // Form validation
    passwordInput.addEventListener('blur', function() {
        const password = this.value;
        const confirmPassword = passwordConfirmInput.value;
        
        if (confirmPassword && password !== confirmPassword) {
            passwordConfirmInput.classList.add('is-invalid');
            let errorDiv = passwordConfirmInput.parentNode.parentNode.querySelector('.password-match-error');
            if (!errorDiv) {
                errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback password-match-error';
                errorDiv.style.display = 'block';
                errorDiv.innerHTML = 'Passwords do not match';
                passwordConfirmInput.parentNode.parentNode.appendChild(errorDiv);
            }
        } else {
            passwordConfirmInput.classList.remove('is-invalid');
            const errorDiv = passwordConfirmInput.parentNode.parentNode.querySelector('.password-match-error');
            if (errorDiv) {
                errorDiv.remove();
            }
        }
    });
    
    passwordConfirmInput.addEventListener('blur', function() {
        const password = passwordInput.value;
        const confirmPassword = this.value;
        
        if (password && confirmPassword && password !== confirmPassword) {
            this.classList.add('is-invalid');
            let errorDiv = this.parentNode.parentNode.querySelector('.password-match-error');
            if (!errorDiv) {
                errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback password-match-error';
                errorDiv.style.display = 'block';
                errorDiv.innerHTML = 'Passwords do not match';
                this.parentNode.parentNode.appendChild(errorDiv);
            }
        } else {
            this.classList.remove('is-invalid');
            const errorDiv = this.parentNode.parentNode.querySelector('.password-match-error');
            if (errorDiv) {
                errorDiv.remove();
            }
        }
    });
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Reset message
        messageDiv.style.display = 'none';
        messageDiv.innerHTML = '';
        
        // Validate passwords match
        if (passwordInput.value !== passwordConfirmInput.value) {
            messageDiv.style.display = 'block';
            messageDiv.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Passwords do not match. Please check and try again.
                </div>
            `;
            return;
        }
        
        // Validate password length
        if (passwordInput.value.length < 6) {
            messageDiv.style.display = 'block';
            messageDiv.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Password must be at least 6 characters long.
                </div>
            `;
            return;
        }
        
        // Show loading
        submitBtn.disabled = true;
        resetSpinner.style.display = 'inline-block';
        
        const formData = new FormData(this);
        
        fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(response => {
            if (response.redirected) {
                window.location.href = response.url;
                return;
            }
            return response.text();
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
            resetSpinner.style.display = 'none';
        });
    });
});
</script>
@endsection