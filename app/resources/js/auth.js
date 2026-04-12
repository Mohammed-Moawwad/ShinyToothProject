/**
 * Setup Image Carousel for Sign Up Page - Display random image on load
 */
function setupImageCarousel() {
    const carousel = document.getElementById("auth-carousel");
    if (!carousel) {
        console.log("Carousel not found on this page");
        return;
    }

    const items = carousel.querySelectorAll(".carousel-item");
    console.log("Found " + items.length + " carousel items");

    if (items.length === 0) {
        console.log("No carousel items found");
        return;
    }

    // Remove active class from all items
    items.forEach((item) => item.classList.remove("active"));

    // Pick a random image (can be the same, that's fine)
    const randomIndex = Math.floor(Math.random() * items.length);
    console.log("Selected random image index: " + randomIndex);

    // Set that image as active
    items[randomIndex].classList.add("active");
    console.log("Carousel activated with random image");
}

/**
 * Setup Login Form Handler
 */
function setupLoginForm() {
    const form = document.getElementById("login-form");
    if (!form) return;

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        // Clear previous errors
        clearErrors("login");

        const email = document.getElementById("email").value;
        const password = document.getElementById("password").value;

        try {
            const response = await fetch("/login", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]',
                    ).content,
                },
                body: JSON.stringify({ email, password }),
            });

            const data = await response.json();

            if (response.ok) {
                // Store token and user type
                localStorage.setItem("auth_token", data.token);
                localStorage.setItem("user_type", data.user_type);

                // Redirect to appropriate dashboard
                const redirectUrl =
                    data.redirect ||
                    (data.user_type === "dentist"
                        ? "/doctor/dashboard"
                        : "/patient/dashboard");
                window.location.href = redirectUrl;
            } else {
                showLoginError(data);
            }
        } catch (error) {
            console.error("Login error:", error);
            showError("login", "An error occurred. Please try again.");
        }
    });
}

/**
 * Setup Registration Form Handler
 */
function setupRegisterForm() {
    const form = document.getElementById("register-form");
    const submitBtn = document.getElementById("register-btn");
    if (!form) return;

    // Setup real-time validation
    setupFormValidation(form, submitBtn);

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        // Clear previous errors
        clearErrors("register");

        // Validate password match
        const password = document.getElementById("password").value;
        const passwordConfirm = document.getElementById(
            "password_confirmation",
        ).value;

        if (password !== passwordConfirm) {
            showError("register", "Passwords do not match.");
            return;
        }

        // Collect form data
        const formData = new FormData(form);
        const data = Object.fromEntries(formData);

        // Add password_confirmation as per Laravel convention
        data.password_confirmation = data.password_confirmation;

        try {
            const response = await fetch("/register", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]',
                    ).content,
                },
                body: JSON.stringify(data),
            });

            const resultData = await response.json();

            if (response.ok) {
                // Store token
                localStorage.setItem("auth_token", resultData.token);
                localStorage.setItem("user_type", "patient");
                // Redirect to dashboard
                window.location.href = "/patient/dashboard";
            } else {
                showRegisterError(resultData);
            }
        } catch (error) {
            console.error("Registration error:", error);
            showError("register", "An error occurred. Please try again.");
        }
    });
}

/**
 * Setup real-time validation for registration form
 */
function setupFormValidation(form, submitBtn) {
    const fields = [
        "full_name",
        "phone",
        "nationality",
        "gender",
        "place_of_birth",
        "date_of_birth",
        "blood_type",
        "email",
        "password",
        "password_confirmation",
    ];

    // Listen to all field changes
    fields.forEach((fieldName) => {
        const field = document.getElementById(fieldName);
        if (field) {
            field.addEventListener("change", () =>
                validateForm(form, submitBtn),
            );
            field.addEventListener("input", () =>
                validateForm(form, submitBtn),
            );
        }
    });

    // Initial validation
    validateForm(form, submitBtn);
}

/**
 * Validate entire registration form
 */
function validateForm(form, submitBtn) {
    const validations = {
        full_name: validateFullName(),
        phone: validatePhone(),
        nationality: validateNationality(),
        gender: validateGender(),
        place_of_birth: validatePlaceOfBirth(),
        date_of_birth: validateDateOfBirth(),
        blood_type: validateBloodType(),
        email: validateEmail(),
        password: validatePasswordStrength(),
        password_confirmation: validatePasswordConfirmation(),
    };

    const allValid = Object.values(validations).every((val) => val);
    submitBtn.disabled = !allValid;
    return allValid;
}

/**
 * Validators for each field
 */
function validateFullName() {
    const field = document.getElementById("full_name");
    return field && field.value.trim().length > 0;
}

function validatePhone() {
    const field = document.getElementById("phone");
    if (!field || !field.value) return false;
    const phoneRegex = /^05[0-9]{8}$/;
    return phoneRegex.test(field.value);
}

function validateNationality() {
    const field = document.getElementById("nationality");
    return field && field.value.trim().length > 0;
}

function validateGender() {
    const gender = document.querySelector('input[name="gender"]:checked');
    return gender !== null;
}

function validatePlaceOfBirth() {
    const field = document.getElementById("place_of_birth");
    return field && field.value.trim().length > 0;
}

function validateDateOfBirth() {
    const field = document.getElementById("date_of_birth");
    return field && field.value.trim().length > 0;
}

function validateBloodType() {
    const bloodType = document.querySelector(
        'input[name="blood_type"]:checked',
    );
    return bloodType !== null;
}

function validateEmail() {
    const field = document.getElementById("email");
    if (!field || !field.value) return false;
    // Must be in format: something@gmail.com
    const emailRegex = /^[^\s@]+@gmail\.com$/;
    return emailRegex.test(field.value);
}

function validatePasswordStrength() {
    const password = document.getElementById("password").value;
    return validatePassword(password);
}

function validatePasswordConfirmation() {
    const password = document.getElementById("password").value;
    const confirmation = document.getElementById("password_confirmation").value;
    return password === confirmation && password.length > 0;
}

/**
 * Display login errors
 */
function showLoginError(data) {
    if (data.errors) {
        // Detailed field errors
        Object.keys(data.errors).forEach((field) => {
            const errorEl = document.getElementById(`${field}-error`);
            if (errorEl) {
                errorEl.textContent = data.errors[field][0];
                errorEl.classList.add("active");
            }
        });
    } else if (data.message) {
        // General error
        showError("login", data.message);
    }
}

/**
 * Display registration errors
 */
function showRegisterError(data) {
    if (data.errors) {
        // Detailed field errors
        Object.keys(data.errors).forEach((field) => {
            const errorEl = document.getElementById(`${field}-error`);
            if (errorEl) {
                errorEl.textContent = data.errors[field][0];
                errorEl.classList.add("active");
            }
        });
    } else if (data.message) {
        // General error
        showError("register", data.message);
    }
}

/**
 * Show general error message
 */
function showError(formType, message) {
    const errorEl = document.getElementById(`${formType}-error`);
    if (errorEl) {
        errorEl.textContent = message;
        errorEl.classList.add("active");
    }
}

/**
 * Clear all error messages
 */
function clearErrors(formType) {
    const errorEl = document.getElementById(`${formType}-error`);
    if (errorEl) {
        errorEl.textContent = "";
        errorEl.classList.remove("active");
    }

    // Clear field-specific errors
    document.querySelectorAll(".error-text.active").forEach((el) => {
        el.classList.remove("active");
        el.textContent = "";
    });
}

/**
 * Password strength validator
 */
function validatePassword(password) {
    const requirements = {
        length: password.length >= 8,
        uppercase: /[A-Z]/.test(password),
        lowercase: /[a-z]/.test(password),
        number: /[0-9]/.test(password),
        special: /[!@#$%^&*]/.test(password),
    };

    return Object.values(requirements).every((req) => req);
}

/**
 * Update password requirement checklist
 */
function updatePasswordRequirements(password) {
    const requirements = {
        "req-length": password.length >= 8,
        "req-uppercase": /[A-Z]/.test(password),
        "req-lowercase": /[a-z]/.test(password),
        "req-number": /[0-9]/.test(password),
        "req-special": /[!@#$%^&*]/.test(password),
    };

    const reqEl = document.getElementById("password-requirements");
    if (password.length > 0) {
        reqEl?.classList.add("active");
    } else {
        reqEl?.classList.remove("active");
    }

    Object.entries(requirements).forEach(([id, met]) => {
        const el = document.getElementById(id);
        if (el) {
            if (met) {
                el.classList.add("met");
            } else {
                el.classList.remove("met");
            }
        }
    });
}

/**
 * Initialize when DOM is ready
 */
document.addEventListener("DOMContentLoaded", function () {
    setupLoginForm();
    setupRegisterForm();
    setupImageCarousel();

    // Setup password strength indicator
    const passwordInput = document.getElementById("password");
    if (passwordInput) {
        passwordInput.addEventListener("input", (e) => {
            updatePasswordRequirements(e.target.value);
        });
    }

    // Setup password visibility toggler for login
    const loginToggle = document.getElementById("login-password-toggle");
    if (loginToggle) {
        const loginPasswordInput = document.getElementById("password");
        const loginPasswordIcon = document.getElementById(
            "login-password-icon",
        );

        loginToggle.addEventListener("click", (e) => {
            e.preventDefault();
            const isPassword = loginPasswordInput.type === "password";
            loginPasswordInput.type = isPassword ? "text" : "password";
            loginPasswordIcon.classList.toggle("bi-eye", !isPassword);
            loginPasswordIcon.classList.toggle("bi-eye-slash", isPassword);
        });
    }

    // Setup password visibility toggler for register
    const registerToggle = document.getElementById("password-toggle");
    if (registerToggle) {
        const registerPasswordInput = document.getElementById("password");
        const registerPasswordIcon =
            document.getElementById("password-eye-icon");

        registerToggle.addEventListener("click", (e) => {
            e.preventDefault();
            const isPassword = registerPasswordInput.type === "password";
            registerPasswordInput.type = isPassword ? "text" : "password";
            registerPasswordIcon.classList.toggle("bi-eye", !isPassword);
            registerPasswordIcon.classList.toggle("bi-eye-slash", isPassword);
        });
    }
});
