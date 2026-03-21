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
        passwordToggle.addEventListener('click', function() {
            const isHidden = passwordInput.type === 'password';
            passwordInput.type = isHidden ? 'text' : 'password';
            const icon = document.getElementById('password-eye-icon') || this.querySelector('i');
            if (icon) {
                icon.className = isHidden ? 'bi bi-eye-slash' : 'bi bi-eye';
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
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length > 10) {
                this.value = this.value.slice(0, 10);
            }
        });
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        // Clear previous errors
        document.getElementById('register-error').classList.remove('active');
        ['first_name','last_name','address','nationality','gender','blood_type',
         'place_of_birth','date_of_birth','phone','email','password','passwordConfirm']
            .forEach(f => clearFieldError(f));

        // Get form values
        const first_name     = document.getElementById('first_name').value.trim();
        const last_name      = document.getElementById('last_name').value.trim();
        const address        = document.getElementById('address').value.trim();
        const nationality    = document.getElementById('nationality').value.trim();
        const genderEl       = document.querySelector('input[name="gender"]:checked');
        const gender         = genderEl ? genderEl.value : '';
        const bloodTypeEl    = document.querySelector('input[name="blood_type"]:checked');
        const blood_type     = bloodTypeEl ? bloodTypeEl.value : '';
        const place_of_birth = document.getElementById('place_of_birth').value.trim();
        const date_of_birth  = document.getElementById('date_of_birth').value;
        const phone          = document.getElementById('phone').value.trim();
        const email          = document.getElementById('email').value.trim();
        const password       = document.getElementById('password').value;
        const passwordConfirm = document.getElementById('password_confirmation').value;

        if (!validateRegisterForm(first_name, last_name, address, nationality, gender,
            blood_type, place_of_birth, date_of_birth, phone, email, password, passwordConfirm)) {
            return;
        }

        try {
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creating account...';

            const response = await fetch(`${API_BASE}/auth/patient/register`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ first_name, last_name, address, nationality, gender,
                    blood_type, place_of_birth, date_of_birth, phone, email, password }),
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Registration failed. Please try again.');
            }

            localStorage.setItem('auth_token', data.token);
            localStorage.setItem('user_role', 'patient');
            if (data.user) localStorage.setItem('user_data', JSON.stringify(data.user));

            window.location.href = '/patient/dashboard';
        } catch (error) {
            const submitBtn = form.querySelector('button[type="submit"]');
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
function validateRegisterForm(first_name, last_name, address, nationality, gender,
    blood_type, place_of_birth, date_of_birth, phone, email, password, passwordConfirm) {
    let isValid = true;

    if (!first_name || first_name.length < 2) {
        showFieldError('first_name', 'First name must be at least 2 characters');
        isValid = false;
    }
    if (!last_name || last_name.length < 2) {
        showFieldError('last_name', 'Second name must be at least 2 characters');
        isValid = false;
    }
    if (!address) {
        showFieldError('address', 'Address is required');
        isValid = false;
    }
    if (!nationality) {
        showFieldError('nationality', 'Nationality is required');
        isValid = false;
    }
    if (!gender) {
        showFieldError('gender', 'Please select a gender');
        isValid = false;
    }
    if (!blood_type) {
        showFieldError('blood_type', 'Please select a blood type');
        isValid = false;
    }
    if (!place_of_birth) {
        showFieldError('place_of_birth', 'Place of birth is required');
        isValid = false;
    }
    if (!date_of_birth) {
        showFieldError('date_of_birth', 'Date of birth is required');
        isValid = false;
    }
    if (!phone) {
        showFieldError('phone', 'Phone number is required');
        isValid = false;
    } else if (!isValidSaudiPhone(phone)) {
        showFieldError('phone', 'Phone must start with 05 followed by 8 digits');
        isValid = false;
    }
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
    } else if (!isPasswordValid(password)) {
        showFieldError('password', 'Password must meet all requirements');
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
