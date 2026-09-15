<!-- Authentication Modal -->
<div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="authModalLabel">Login / Signup</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Tabs -->
                <ul class="nav nav-tabs mb-3" id="authTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="login-tab-cart" data-bs-toggle="tab" data-bs-target="#login-cart" type="button" role="tab">Login</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="signup-tab-cart" data-bs-toggle="tab" data-bs-target="#signup-cart" type="button" role="tab">Signup</button>
                    </li>
                </ul>

                <div class="tab-content" id="authTabContent">
                    <!-- Login Form -->
                    <div class="tab-pane fade show active" id="login-cart" role="tabpanel">
                        <form id="customerLoginFormCart">
                            @csrf
                            <div class="mb-3">
                                <label for="loginEmailCart" class="form-label">Email *</label>
                                <input type="email" class="form-control w-100" id="loginEmailCart" name="email" required>
                                <div class="invalid-feedback" id="loginEmailErrorCart"></div>
                            </div>
                            <div class="mb-3">
                                <label for="loginPasswordCart" class="form-label">Password *</label>
                                <input type="password" class="form-control w-100" id="loginPasswordCart" name="password" required>
                                <div class="invalid-feedback" id="loginPasswordErrorCart"></div>
                                 <span class="togglePassword position-absolute top-50 end-0 translate-middle-y me-3" style="cursor:pointer;">
                               <i class="fa fa-eye"></i>
                                </span>
                            </div>
                            
                            
                            <div class="mb-3 text-center">
                                <a href="{{ route('customer.password.request', ['return_url' => url()->current()]) }}" class="text-primary small" onclick="closeAuthModalCart()">Forgot Password?</a>
                            </div>
                            <div id="loginMessageCart" class="mb-3" style="display:none;"></div>
                            <button type="submit" class="btn w-100" id="loginBtnCart">
                                <span class="spinner-border spinner-border-sm me-2" id="loginSpinnerCart" style="display:none;"></span>
                                Login
                            </button>
                        </form>
                    </div>

                    <!-- Signup Form with OTP -->
                    <div class="tab-pane fade" id="signup-cart" role="tabpanel">
                        <!-- Step 1: Email and OTP -->
                        <div id="signupStep1Cart">
                            <div class="mb-3">
                                <input type="email" class="form-control w-100" id="signupEmailCart" placeholder="Email" required>
                                <div id="signupEmailErrorCart" class="text-danger small mt-1" style="display: none;"></div>
                            </div>
                            <button class="btn w-100 mb-3" id="sendOtpBtnCart">Send OTP</button>
                            <div id="otpSectionCart" style="display: none;">
                                <div class="mb-3">
                                    <input type="text" class="form-control" id="signupOtpCart" placeholder="Enter OTP" maxlength="6">
                                    <div id="otpErrorCart" class="text-danger small mt-1" style="display: none;"></div>
                                </div>
                                <button class="btn btn-primary w-100 mb-3" id="verifyOtpBtnCart">Verify OTP</button>
                                <div id="otpMessageCart" class="small mb-3" style="display: none;"></div>
                            </div>
                        </div>

                        <!-- Step 2: Complete Registration -->
                        <div id="signupStep2Cart" style="display: none;">
                            <div class="mb-3">
                                <input type="text" class="form-control" id="signupNameCart" placeholder="Full Name" required>
                                <div id="signupNameErrorCart" class="text-danger small mt-1" style="display: none;"></div>
                            </div>
                            <div class="mb-3">
                                <input type="tel" class="form-control" id="signupPhoneCart" placeholder="Phone Number (10 digits)" maxlength="10" required>
                                <div id="phoneErrorCart" class="text-danger small mt-1" style="display: none;"></div>
                            </div>
                            <!--<div class="mb-3">-->
                            <!--    <input type="password" class="form-control" id="signupPasswordCart" placeholder="Password (Min 8 chars with uppercase, lowercase, number & special)" required>-->
                            <!--    <div id="signupPasswordErrorCart" class="text-danger small mt-1" style="display: none;"></div>-->
                            <!--</div>-->
                            
                            <div class="mb-3 position-relative">
                                
                                    <input type="password" class="form-control"
                                           id="signupPasswordCart"
                                           placeholder="Password (Min 8 chars with uppercase, lowercase, number & special)"
                                           required
                                           style="padding-right: 40px;">
                            
                                    <span class="togglePasswordCartNew position-absolute top-50 translate-middle-y"
                                          style="cursor:pointer; right: 10px;">
                                        <i class="fa fa-eye"></i>
                                    </span>
                               
                            
                                <div id="signupPasswordErrorCart" class="text-danger small mt-1" style="display: none;"></div>
                            </div>
                            
                            
                            <!--<div class="mb-3">-->
                            <!--    <input type="password" class="form-control" id="signupConfirmPasswordCart" placeholder="Confirm Password" required>-->
                            <!--    <div id="signupConfirmPasswordErrorCart" class="text-danger small mt-1" style="display: none;"></div>-->
                            <!--</div>-->
                            
                            
                            <div class="mb-3 position-relative">
                               
                                    <input type="password" class="form-control"
                                           id="signupConfirmPasswordCart"
                                           placeholder="Confirm Password"
                                           required
                                           style="padding-right: 40px;">
                            
                                    <span class="togglePasswordCartNew position-absolute top-50 translate-middle-y"
                                          style="cursor:pointer; right: 10px;">
                                        <i class="fa fa-eye"></i>
                                    </span>
                              
                            
                                <div id="signupConfirmPasswordErrorCart" class="text-danger small mt-1" style="display: none;"></div>
                            </div>
                            
                            <div id="signupErrorCart" class="text-danger small mb-3" style="display: none;"></div>
                            <div id="signupMessageCart" class="mb-3" style="display: none;"></div>
                            <button class="btn w-100" id="signupBtnCart">
                                <span class="spinner-border spinner-border-sm me-2" id="signupSpinnerCart" style="display:none;"></span>
                                Signup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-label { display: flex; justify-content: left; }
    #customerLoginFormCart button { background-color: #cb7000; }
    #customerLoginFormCart button:hover { color: #c9c6c6; }
    #signupStep1Cart button { background-color: #cb7000; }
    #signupStep1Cart button:hover { color: #c9c6c6; }
    #signup-tab-cart { color: #5f656b; }
    #login-tab-cart { color: #5f656b; }
    .modal-body { padding: 0px 16px 16px 16px; }

        
   #signupBtnCart {
    margin-top: 15px;
    padding: 10px 20px;
    border: 1px solid grey;
    border-radius: 6px;
    color: #fff !important;
    cursor: pointer;
    background-color: #cb7000;
}

.end-0 {
    right: 10px !important;
}

.top-50 {
    top: 60% !important;
}

.modal-content input{
        width: 100%;
}

  @media (max-width: 991px) {
.modal-content{
         width: 100%;
}
    
}
</style>

<script>
// ==============================================
// OTP-BASED AUTH MODAL JAVASCRIPT (REUSABLE)
// ==============================================
(function() {
    // Store modal instance globally so other pages can open it
    let authModal = null;

    document.addEventListener('DOMContentLoaded', function() {
        const modalElement = document.getElementById('authModal');
        if (modalElement) {
            authModal = new bootstrap.Modal(modalElement);

            // ========== LOGIN HANDLER ==========
            const loginForm = document.getElementById('customerLoginFormCart');
            if (loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    handleLogin();
                });
            }

            // ========== SIGNUP HANDLERS ==========
            const sendOtpBtn = document.getElementById('sendOtpBtnCart');
            if (sendOtpBtn) {
                sendOtpBtn.addEventListener('click', sendOtp);
            }

            const verifyOtpBtn = document.getElementById('verifyOtpBtnCart');
            if (verifyOtpBtn) {
                verifyOtpBtn.addEventListener('click', verifyOtp);
            }

            const signupBtn = document.getElementById('signupBtnCart');
            if (signupBtn) {
                signupBtn.addEventListener('click', completeSignup);
            }

            // ========== TAB SWITCHING ==========
            const loginTab = document.getElementById('login-tab-cart');
            const signupTab = document.getElementById('signup-tab-cart');

            if (loginTab) {
                loginTab.addEventListener('click', function() {
                    clearSignupErrors();
                    resetSignupForm();
                });
            }
            if (signupTab) {
                signupTab.addEventListener('click', function() {
                    clearLoginErrors();
                    hideLoginMessage();
                });
            }

            // ========== MODAL HIDDEN EVENT ==========
            modalElement.addEventListener('hidden.bs.modal', function() {
                // Clean up backdrop and body classes
                document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';

                // Reset forms
                resetSignupForm();
                clearLoginErrors();
                hideLoginMessage();
            });
        }

        // Check if we need to open modal after password reset
        const openLoginModal = {{ session('open_login_modal') ? 'true' : 'false' }};
        if (openLoginModal) {
            setTimeout(() => {
                if (authModal) {
                    authModal.show();
                    // Switch to login tab
                    const loginTab = document.getElementById('login-tab-cart');
                    if (loginTab) loginTab.click();
                    // Show success message
                    const loginMessage = document.getElementById('loginMessageCart');
                    if (loginMessage) {
                        loginMessage.innerHTML = `
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                Password reset successful! Please login with your new password.
                            </div>
                        `;
                        loginMessage.style.display = 'block';
                    }
                }
            }, 500);
        }
    });

    // Global function to open the auth modal from any page
    window.openAuthModal = function() {
        if (authModal) {
            authModal.show();
            resetSignupForm();
            clearLoginErrors();
            hideLoginMessage();
        } else {
            console.error('Auth modal not initialized');
        }
    };

    // Global function to close the modal
    window.closeAuthModal = function() {
        if (authModal) {
            authModal.hide();
        }
    };

    // ========== LOGIN FUNCTION ==========
    function handleLogin() {
        const loginBtn = document.getElementById('loginBtnCart');
        const loginSpinner = document.getElementById('loginSpinnerCart');
        const loginMessage = document.getElementById('loginMessageCart');

        clearLoginErrors();
        loginMessage.style.display = 'none';

        const email = document.getElementById('loginEmailCart').value.trim();
        const password = document.getElementById('loginPasswordCart').value;

        if (!email || !password) {
            loginMessage.innerHTML = '<div class="alert alert-danger">Please fill in all fields.</div>';
            loginMessage.style.display = 'block';
            return;
        }

        loginBtn.disabled = true;
        loginSpinner.style.display = 'inline-block';

        fetch('/customer/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                email: email,
                password: password,
                redirect_to_checkout: '1',
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loginMessage.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                loginMessage.style.display = 'block';
                setTimeout(() => {
                    authModal.hide();
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        window.location.reload();
                    }
                }, 800);
            } else {
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        const errorElement = document.getElementById(`login${field.charAt(0).toUpperCase() + field.slice(1)}ErrorCart`);
                        if (errorElement) {
                            errorElement.textContent = data.errors[field][0];
                            errorElement.style.display = 'block';
                            const inputField = document.getElementById(`login${field.charAt(0).toUpperCase() + field.slice(1)}Cart`);
                            if (inputField) inputField.classList.add('is-invalid');
                        }
                    });
                } else {
                    loginMessage.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
                    loginMessage.style.display = 'block';
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            loginMessage.innerHTML = '<div class="alert alert-danger">Something went wrong. Please try again.</div>';
            loginMessage.style.display = 'block';
        })
        .finally(() => {
            loginBtn.disabled = false;
            loginSpinner.style.display = 'none';
        });
    }

    // ========== SIGNUP FUNCTIONS ==========
    let verifiedEmail = '';
    let otpResendTimer = null;
    let otpCountdown = 60;

    function sendOtp() {
        const email = document.getElementById('signupEmailCart').value.trim();
        const emailError = document.getElementById('signupEmailErrorCart');
        const btn = document.getElementById('sendOtpBtnCart');

        emailError.style.display = 'none';

        if (!email || !validateEmail(email)) {
            emailError.textContent = 'Please enter a valid email.';
            emailError.style.display = 'block';
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Sending...';

        fetch('/customer/send-signup-otp', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ email: email })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                verifiedEmail = email;
                document.getElementById('otpSectionCart').style.display = 'block';
                const otpMessage = document.getElementById('otpMessageCart');
                otpMessage.textContent = 'OTP sent to your email. Please check your inbox.';
                otpMessage.style.color = 'green';
                otpMessage.style.display = 'block';
                startOtpResendTimer();
            } else {
                if (data.errors && data.errors.email) {
                    emailError.textContent = data.errors.email[0];
                    emailError.style.display = 'block';
                } else {
                    emailError.textContent = data.message || 'Failed to send OTP.';
                    emailError.style.display = 'block';
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            emailError.textContent = 'Something went wrong. Please try again.';
            emailError.style.display = 'block';
        })
        .finally(() => {
            btn.disabled = false;
            btn.textContent = 'Send OTP';
        });
    }

    function verifyOtp() {
        const otp = document.getElementById('signupOtpCart').value.trim();
        const otpError = document.getElementById('otpErrorCart');
        const otpMessage = document.getElementById('otpMessageCart');
        const btn = document.getElementById('verifyOtpBtnCart');

        otpError.style.display = 'none';

        if (!otp || otp.length !== 6 || !/^\d+$/.test(otp)) {
            otpError.textContent = 'Please enter a valid 6-digit OTP.';
            otpError.style.display = 'block';
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Verifying...';

        fetch('/customer/verify-signup-otp', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ email: verifiedEmail, otp: otp })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                otpMessage.textContent = 'Email verified successfully!';
                otpMessage.style.color = 'green';
                otpMessage.style.display = 'block';
                setTimeout(() => {
                    document.getElementById('signupStep1Cart').style.display = 'none';
                    document.getElementById('signupStep2Cart').style.display = 'block';
                    if (otpResendTimer) clearInterval(otpResendTimer);
                }, 800);
            } else {
                otpError.textContent = data.message || 'Invalid OTP.';
                otpError.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            otpError.textContent = 'Something went wrong. Please try again.';
            otpError.style.display = 'block';
        })
        .finally(() => {
            btn.disabled = false;
            btn.textContent = 'Verify OTP';
        });
    }

    function completeSignup() {
        const name = document.getElementById('signupNameCart').value.trim();
        const phone = document.getElementById('signupPhoneCart').value.trim();
        const password = document.getElementById('signupPasswordCart').value;
        const confirmPassword = document.getElementById('signupConfirmPasswordCart').value;
        const email = verifiedEmail;
        const signupSpinner = document.getElementById('signupSpinnerCart');
        const signupMessage = document.getElementById('signupMessageCart');

        clearSignupErrors();
        signupMessage.style.display = 'none';

        let isValid = true;

        if (!name) { showError('signupNameErrorCart', 'Name is required.'); isValid = false; }
        if (!phone) { showError('phoneErrorCart', 'Phone number is required.'); isValid = false; }
        else if (!/^\d{10}$/.test(phone)) { showError('phoneErrorCart', 'Please enter a valid 10-digit phone number.'); isValid = false; }
        if (!password) { showError('signupPasswordErrorCart', 'Password is required.'); isValid = false; }
        else if (!validatePassword(password)) { showError('signupPasswordErrorCart', 'Password must be at least 8 characters with uppercase, lowercase, number and special character.'); isValid = false; }
        if (!confirmPassword) { showError('signupConfirmPasswordErrorCart', 'Please confirm your password.'); isValid = false; }
        else if (password !== confirmPassword) { showError('signupConfirmPasswordErrorCart', 'Passwords do not match.'); isValid = false; }

        if (!isValid) return;

        const btn = document.getElementById('signupBtnCart');
        btn.disabled = true;
        signupSpinner.style.display = 'inline-block';

        const formData = new FormData();
        formData.append('name', name);
        formData.append('email', email);
        formData.append('phone', phone);
        formData.append('password', password);
        formData.append('password_confirmation', confirmPassword);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        fetch('/customer/register', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                signupMessage.innerHTML = '<div class="alert alert-success">Registration successful! Logging you in...</div>';
                signupMessage.style.display = 'block';
                setTimeout(() => {
                    authModal.hide();
                    window.location.href = '/customer/checkout';
                }, 800);
            } else {
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        let errorId = '';
                        if (field === 'name') errorId = 'signupNameErrorCart';
                        else if (field === 'email') errorId = 'signupEmailErrorCart';
                        else if (field === 'phone') errorId = 'phoneErrorCart';
                        else if (field === 'password') errorId = 'signupPasswordErrorCart';
                        else if (field === 'password_confirmation') errorId = 'signupConfirmPasswordErrorCart';
                        if (errorId) showError(errorId, data.errors[field][0]);
                    });
                } else {
                    document.getElementById('signupErrorCart').textContent = data.message || 'Registration failed.';
                    document.getElementById('signupErrorCart').style.display = 'block';
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('signupErrorCart').textContent = 'Something went wrong. Please try again.';
            document.getElementById('signupErrorCart').style.display = 'block';
        })
        .finally(() => {
            btn.disabled = false;
            signupSpinner.style.display = 'none';
        });
    }

    // ========== HELPER FUNCTIONS ==========
    function validateEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    function validatePassword(pass) {
        return /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/.test(pass);
    }

    function showError(elementId, message) {
        const el = document.getElementById(elementId);
        if (el) {
            el.textContent = message;
            el.style.display = 'block';
        }
    }

    function startOtpResendTimer() {
        const sendOtpBtn = document.getElementById('sendOtpBtnCart');
        const otpMessage = document.getElementById('otpMessageCart');
        if (!sendOtpBtn || !otpMessage) return;

        sendOtpBtn.disabled = true;
        otpCountdown = 60;

        if (otpResendTimer) clearInterval(otpResendTimer);
        otpResendTimer = setInterval(() => {
            otpCountdown--;
            if (otpCountdown <= 0) {
                clearInterval(otpResendTimer);
                sendOtpBtn.disabled = false;
                sendOtpBtn.innerHTML = 'Resend OTP';
                otpMessage.innerHTML = 'Didn\'t receive OTP? Click Resend OTP.';
            } else {
                sendOtpBtn.innerHTML = `Resend OTP in ${otpCountdown}s`;
            }
        }, 1000);
    }

    function resetSignupForm() {
        ['signupEmailCart', 'signupOtpCart', 'signupNameCart', 'signupPhoneCart', 'signupPasswordCart', 'signupConfirmPasswordCart']
            .forEach(id => {
                const el = document.getElementById(id);
                if (el) el.value = '';
            });

        const step1 = document.getElementById('signupStep1Cart');
        const step2 = document.getElementById('signupStep2Cart');
        const otpSection = document.getElementById('otpSectionCart');
        const sendOtpBtn = document.getElementById('sendOtpBtnCart');

        if (step1) step1.style.display = 'block';
        if (step2) step2.style.display = 'none';
        if (otpSection) otpSection.style.display = 'none';
        if (sendOtpBtn) {
            sendOtpBtn.disabled = false;
            sendOtpBtn.innerHTML = 'Send OTP';
        }

        clearSignupErrors();

        if (otpResendTimer) {
            clearInterval(otpResendTimer);
            otpResendTimer = null;
        }
        verifiedEmail = '';
    }

    function clearSignupErrors() {
        ['signupEmailErrorCart', 'otpErrorCart', 'signupNameErrorCart', 'phoneErrorCart', 'signupPasswordErrorCart', 'signupConfirmPasswordErrorCart', 'signupErrorCart']
            .forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.style.display = 'none';
                    el.textContent = '';
                }
            });
        ['signupMessageCart', 'otpMessageCart'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.style.display = 'none';
                el.innerHTML = '';
            }
        });
    }

    function clearLoginErrors() {
        ['loginEmailCart', 'loginPasswordCart'].forEach(field => {
            const el = document.getElementById(field);
            if (el) el.classList.remove('is-invalid');
        });
        ['loginEmailErrorCart', 'loginPasswordErrorCart'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.style.display = 'none';
        });
    }

    function hideLoginMessage() {
        const msg = document.getElementById('loginMessageCart');
        if (msg) msg.style.display = 'none';
    }
})();
</script>





<!-- password hide or show karna ke liye js -->

<script>
    document.addEventListener("DOMContentLoaded", function() {
    const toggles = document.querySelectorAll(".togglePassword");

    toggles.forEach(toggle => {
        const passwordInput = toggle.closest("div").querySelector("input");
        const icon = toggle.querySelector("i");

        toggle.addEventListener("click", function() {
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                passwordInput.type = "password";
                icon.classList.replace("fa-eye-slash", "fa-eye");
            }
        });
    });
});
</script>

<!-- password hide or show karna ke liye js cretnew -->
<script>
    
    document.addEventListener("DOMContentLoaded", function() {
    const cartToggles = document.querySelectorAll(".togglePasswordCartNew");

    cartToggles.forEach(function(toggle) {
        const inputField = toggle.parentElement.querySelector("input");
        const icon = toggle.querySelector("i");

        toggle.addEventListener("click", function() {
            if (inputField.type === "password") {
                inputField.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                inputField.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        });
    });
});
</script>