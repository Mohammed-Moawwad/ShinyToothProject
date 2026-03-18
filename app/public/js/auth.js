// API Configuration
const API_BASE = '/api';

/**
 * Setup Login Form Handler
 */
function setupLoginForm() {
    const form = document.getElementById('login-form');
    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        // Clear previous errors
        document.getElementById('login-error').classList.remove('active');
        clearFieldError('email');
        clearFieldError('password');

        // Get form values
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;
        const role = 'patient'; // Default to patient

        // Validate
        if (!validateLoginForm(email, password)) {
            return;
        }

        try {
            // Disable button
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Signing in...';

            // Make API call
            const endpoint = '/auth/patient/login';
            const response = await fetch(`${API_BASE}${endpoint}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ email, password }),
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Login failed. Please try again.');
            }

            // Store auth data
            localStorage.setItem('auth_token', data.token);
            localStorage.setItem('user_role', role);
            if (data.user) {
                localStorage.setItem('user_data', JSON.stringify(data.user));
            }

            // Redirect to dashboard
            const dashboard = '/patient/dashboard';
            window.location.href = dashboard;
        } catch (error) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Sign In';
            showLoginError(error.message);
        }
    });
}

/**
 * Setup Register Form Handler
 */
function setupRegisterForm() {
    const form = document.getElementById('register-form');
    if (!form) return;

    // Password visibility toggle
    const passwordInput = document.getElementById('password');
    const passwordToggle = document.getElementById('password-toggle');
    if (passwordToggle && passwordInput) {
        passwordToggle.addEventListener('click', function(e) {
            e.preventDefault();
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Toggle icon
            const icon = this.querySelector('i');
            if (type === 'text') {
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    }

    // Real-time password validation
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            validatePasswordRequirements(this.value);
        });
    }

    // Phone number input restriction
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            // Remove non-numeric characters
            this.value = this.value.replace(/[^0-9]/g, '');
            
            // Limit to 10 digits
            if (this.value.length > 10) {
                this.value = this.value.slice(0, 10);
            }
        });
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        // Clear previous errors
        document.getElementById('register-error').classList.remove('active');
        clearFieldError('first_name');
        clearFieldError('last_name');
        clearFieldError('email');
        clearFieldError('phone');
        clearFieldError('password');
        clearFieldError('passwordConfirm');

        // Get form values
        const first_name = document.getElementById('first_name').value.trim();
        const last_name = document.getElementById('last_name').value.trim();
        const email = document.getElementById('email').value.trim();
        const phone = document.getElementById('phone').value.trim();
        const password = document.getElementById('password').value;
        const passwordConfirm = document.getElementById('password_confirmation').value;
        const role = 'patient'; // Default to patient

        // Validate
        if (!validateRegisterForm(first_name, last_name, email, phone, password, passwordConfirm)) {
            return;
        }

        try {
            // Disable button
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creating account...';

            // Make API call
            const endpoint = '/auth/patient/register';
            const response = await fetch(`${API_BASE}${endpoint}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ first_name, last_name, email, phone, password }),
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Registration failed. Please try again.');
            }

            // Store auth data
            localStorage.setItem('auth_token', data.token);
            localStorage.setItem('user_role', role);
            if (data.user) {
                localStorage.setItem('user_data', JSON.stringify(data.user));
            }

            // Redirect to dashboard
            const dashboard = '/patient/dashboard';
            window.location.href = dashboard;
        } catch (error) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Create Account';
            showRegisterError(error.message);
        }
    });
}

/**
 * Validate Login Form
 */
function validateLoginForm(email, password) {
    let isValid = true;

    if (!email) {
        showFieldError('email', 'Email is required');
        isValid = false;
    } else if (!isValidEmail(email)) {
        showFieldError('email', 'Please enter a valid email');
        isValid = false;
    }

    if (!password) {
        showFieldError('password', 'Password is required');
        isValid = false;
    } else if (password.length < 6) {
        showFieldError('password', 'Password must be at least 6 characters');
        isValid = false;
    }

    return isValid;
}

/**
 * Validate Register Form
 */
function validateRegisterForm(first_name, last_name, email, phone, password, passwordConfirm) {
    let isValid = true;

    if (!first_name) {
        showFieldError('first_name', 'First name is required');
        isValid = false;
    } else if (first_name.length < 2) {
        showFieldError('first_name', 'First name must be at least 2 characters');
        isValid = false;
    }

    if (!last_name) {
        showFieldError('last_name', 'Last name is required');
        isValid = false;
    } else if (last_name.length < 2) {
        showFieldError('last_name', 'Last name must be at least 2 characters');
        isValid = false;
    }

    if (!email) {
        showFieldError('email', 'Email is required');
        isValid = false;
    } else if (!isValidEmail(email)) {
        showFieldError('email', 'Please enter a valid email');
        isValid = false;
    }

    if (!phone) {
        showFieldError('phone', 'Phone number is required');
        isValid = false;
    } else if (!isValidSaudiPhone(phone)) {
        showFieldError('phone', 'Phone must start with 05 followed by 8 digits');
        isValid = false;
    }

    if (!password) {
        showFieldError('password', 'Password is required');
        isValid = false;
    } else if (!isPasswordValid(password)) {
        showFieldError('password', 'Password must meet all requirements (8+ chars, uppercase, lowercase, number, special char)');
        isValid = false;
    }

    if (!passwordConfirm) {
        showFieldError('passwordConfirm', 'Please confirm your password');
        isValid = false;
    } else if (password !== passwordConfirm) {
        showFieldError('passwordConfirm', 'Passwords do not match');
        isValid = false;
    }

    return isValid;
}

/**
 * Show Login Error Alert
 */
function showLoginError(message) {
    const errorDiv = document.getElementById('login-error');
    if (errorDiv) {
        errorDiv.textContent = message;
        errorDiv.classList.add('active');
    }
}

/**
 * Show Register Error Alert
 */
function showRegisterError(message) {
    const errorDiv = document.getElementById('register-error');
    if (errorDiv) {
        errorDiv.textContent = message;
        errorDiv.classList.add('active');
    }
}

/**
 * Show Field-Level Error
 */
function showFieldError(fieldName, message) {
    const errorElement = document.getElementById(`${fieldName}-error`);
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.classList.add('active');
    }
}

/**
 * Clear Field-Level Error
 */
function clearFieldError(fieldName) {
    const errorElement = document.getElementById(`${fieldName}-error`);
    if (errorElement) {
        errorElement.textContent = '';
        errorElement.classList.remove('active');
    }
}

/**
 * Validate Email Format
 */
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

/**
 * Validate Password Requirements in Real-Time
 */
function validatePasswordRequirements(password) {
    const requirementsBox = document.getElementById('password-requirements');
    if (!password) {
        requirementsBox.classList.remove('active');
        return;
    }
    
    requirementsBox.classList.add('active');
    
    const requirements = {
        length: password.length >= 8,
        uppercase: /[A-Z]/.test(password),
        lowercase: /[a-z]/.test(password),
        number: /[0-9]/.test(password),
        special: /[!@#$%^&*]/.test(password)
    };
    
    // Update requirement items
    updateRequirement('req-length', requirements.length);
    updateRequirement('req-uppercase', requirements.uppercase);
    updateRequirement('req-lowercase', requirements.lowercase);
    updateRequirement('req-number', requirements.number);
    updateRequirement('req-special', requirements.special);
}

/**
 * Update Requirement Item Visual State
 */
function updateRequirement(elementId, isMet) {
    const element = document.getElementById(elementId);
    if (element) {
        if (isMet) {
            element.classList.add('met');
        } else {
            element.classList.remove('met');
        }
    }
}

/**
 * Check if Password Meets All Requirements
 */
function isPasswordValid(password) {
    return password.length >= 8 &&
           /[A-Z]/.test(password) &&
           /[a-z]/.test(password) &&
           /[0-9]/.test(password) &&
           /[!@#$%^&*]/.test(password);
}

/**
 * Validate Saudi Phone Number Format (05XXXXXXXX)
 */
function isValidSaudiPhone(phone) {
    const phoneRegex = /^05[0-9]{8}$/;
    return phoneRegex.test(phone);
}

/**
 * Get Stored Auth Token
 */
function getAuthToken() {
    return localStorage.getItem('auth_token');
}

/**
 * Get Stored User Role
 */
function getUserRole() {
    return localStorage.getItem('user_role');
}

/**
 * Get Stored User Data
 */
function getUserData() {
    const data = localStorage.getItem('user_data');
    return data ? JSON.parse(data) : null;
}

/**
 * Check if User is Authenticated
 */
function isAuthenticated() {
    return !!getAuthToken();
}

/**
 * Logout User
 */
function logout() {
    localStorage.removeItem('auth_token');
    localStorage.removeItem('user_role');
    localStorage.removeItem('user_data');
    window.location.href = '/login';
}
