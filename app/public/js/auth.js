// API Configuration
const API_BASE = "/api";

/**
 * Setup Login Form Handler
 */
function setupLoginForm() {
    // Form now submits natively via HTML POST action
    // No JS interception needed
}

/**
 * Setup Register Form Handler
 */
function setupRegisterForm() {
    const form = document.getElementById("register-form");
    if (!form) return;

    // Password visibility toggle
    const passwordInput = document.getElementById("password");
    const passwordToggle = document.getElementById("password-toggle");
    if (passwordToggle && passwordInput) {
        passwordToggle.addEventListener("click", function () {
            const isHidden = passwordInput.type === "password";
            passwordInput.type = isHidden ? "text" : "password";
            const icon =
                document.getElementById("password-eye-icon") ||
                this.querySelector("i");
            if (icon) {
                icon.className = isHidden ? "bi bi-eye-slash" : "bi bi-eye";
            }
        });
    }

    // Real-time password validation
    if (passwordInput) {
        passwordInput.addEventListener("input", function () {
            validatePasswordRequirements(this.value);
        });
    }

    // Phone number input restriction
    const phoneInput = document.getElementById("phone");
    if (phoneInput) {
        phoneInput.addEventListener("input", function (e) {
            this.value = this.value.replace(/[^0-9]/g, "");
            if (this.value.length > 10) {
                this.value = this.value.slice(0, 10);
            }
        });
    }

    // ── Real-time button enable/disable ─────────────────────────────────
    const registerBtn = document.getElementById("register-btn");

    function checkFormReady() {
        const fullName = (
            document.getElementById("full_name")?.value || ""
        ).trim();
        const phone = (document.getElementById("phone")?.value || "").trim();
        const nationality = (
            document.getElementById("nationality")?.value || ""
        ).trim();
        const placeOfBirth = (
            document.getElementById("place_of_birth")?.value || ""
        ).trim();
        const dob = (
            document.getElementById("date_of_birth")?.value || ""
        ).trim();
        const email = (document.getElementById("email")?.value || "").trim();
        const password = document.getElementById("password")?.value || "";
        const confirm =
            document.getElementById("password_confirmation")?.value || "";
        const gender = document.querySelector('input[name="gender"]:checked');
        const bloodType = document.querySelector(
            'input[name="blood_type"]:checked',
        );

        const ready =
            fullName.length >= 2 &&
            /^05[0-9]{8}$/.test(phone) &&
            nationality.length > 0 &&
            placeOfBirth.length > 0 &&
            dob.length > 0 &&
            /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email) &&
            isPasswordValid(password) &&
            confirm.length > 0 &&
            password === confirm &&
            gender !== null &&
            bloodType !== null;

        if (registerBtn) {
            if (ready) {
                registerBtn.classList.remove("btn-auth-notready");
            } else {
                registerBtn.classList.add("btn-auth-notready");
            }
        }
    }

    // Watch all required fields (both input AND change — date pickers only fire 'change')
    [
        "full_name",
        "phone",
        "nationality",
        "place_of_birth",
        "date_of_birth",
        "email",
        "password",
        "password_confirmation",
    ].forEach((id) => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener("input", checkFormReady);
            el.addEventListener("change", checkFormReady);
        }
    });
    document
        .querySelectorAll('input[name="gender"], input[name="blood_type"]')
        .forEach((el) => el.addEventListener("change", checkFormReady));

    // Run once on load in case browser autofills
    checkFormReady();
    // ────────────────────────────────────────────────────────────────────

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        // Clear previous errors
        document.getElementById("register-error").classList.remove("active");
        [
            "full_name",
            "address",
            "nationality",
            "gender",
            "blood_type",
            "place_of_birth",
            "date_of_birth",
            "phone",
            "email",
            "password",
            "passwordConfirm",
        ].forEach((f) => clearFieldError(f));

        // Get form values
        const full_name = document.getElementById("full_name").value.trim();
        const address = document.getElementById("address").value.trim();
        const nationality = document.getElementById("nationality").value.trim();
        const genderEl = document.querySelector('input[name="gender"]:checked');
        const gender = genderEl ? genderEl.value : "";
        const bloodTypeEl = document.querySelector(
            'input[name="blood_type"]:checked',
        );
        const blood_type = bloodTypeEl ? bloodTypeEl.value : "";
        const place_of_birth = document
            .getElementById("place_of_birth")
            .value.trim();
        const date_of_birth = document.getElementById("date_of_birth").value;
        const phone = document.getElementById("phone").value.trim();
        const email = document.getElementById("email").value.trim();
        const password = document.getElementById("password").value;
        const passwordConfirm = document.getElementById(
            "password_confirmation",
        ).value;

        if (
            !validateRegisterForm(
                full_name,
                address,
                nationality,
                gender,
                blood_type,
                place_of_birth,
                date_of_birth,
                phone,
                email,
                password,
                passwordConfirm,
            )
        ) {
            return;
        }

        try {
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML =
                '<span class="spinner-border spinner-border-sm me-2"></span>Creating account...';

            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const response = await fetch(`/register`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    "X-CSRF-TOKEN": csrfMeta ? csrfMeta.content : "",
                },
                body: JSON.stringify({
                    full_name,
                    address,
                    nationality,
                    gender,
                    blood_type,
                    place_of_birth,
                    date_of_birth,
                    phone,
                    email,
                    password,
                    password_confirmation: passwordConfirm,
                }),
            });

            const data = await response.json();

            if (!response.ok) {
                const msg =
                    data.message ||
                    (data.errors
                        ? Object.values(data.errors).flat().join(" ")
                        : "Registration failed. Please try again.");
                throw new Error(msg);
            }

            // Store auth in sessionStorage so homepage header updates correctly
            // (and users aren't unintentionally kept logged in across browser restarts).
            sessionStorage.setItem("auth_token", data.token);
            sessionStorage.setItem("user_role", "patient");
            sessionStorage.setItem("user_type", "patient");
            localStorage.setItem("auth_token", data.token);
            localStorage.setItem("user_role", "patient");
            localStorage.setItem("user_type", "patient");
            if (data.patient) {
                sessionStorage.setItem("user_data", JSON.stringify(data.patient));
                localStorage.setItem("user_data", JSON.stringify(data.patient));
            }

            window.location.href = "/patient/dashboard";
        } catch (error) {
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.disabled = false;
            submitBtn.innerHTML = "Create Account";
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
        showFieldError("email", "Email is required");
        isValid = false;
    } else if (!isValidEmail(email)) {
        showFieldError("email", "Please enter a valid email");
        isValid = false;
    }

    if (!password) {
        showFieldError("password", "Password is required");
        isValid = false;
    } else if (password.length < 6) {
        showFieldError("password", "Password must be at least 6 characters");
        isValid = false;
    }

    return isValid;
}

/**
 * Validate Register Form
 */
function validateRegisterForm(
    full_name,
    address,
    nationality,
    gender,
    blood_type,
    place_of_birth,
    date_of_birth,
    phone,
    email,
    password,
    passwordConfirm,
) {
    let isValid = true;

    if (!full_name || full_name.length < 2) {
        showFieldError("full_name", "Full name must be at least 2 characters");
        isValid = false;
    }
    if (!nationality) {
        showFieldError("nationality", "Nationality is required");
        isValid = false;
    }
    if (!gender) {
        showFieldError("gender", "Please select a gender");
        isValid = false;
    }
    if (!blood_type) {
        showFieldError("blood_type", "Please select a blood type");
        isValid = false;
    }
    if (!place_of_birth) {
        showFieldError("place_of_birth", "Place of birth is required");
        isValid = false;
    }
    if (!date_of_birth) {
        showFieldError("date_of_birth", "Date of birth is required");
        isValid = false;
    }
    if (!phone) {
        showFieldError("phone", "Phone number is required");
        isValid = false;
    } else if (!isValidSaudiPhone(phone)) {
        showFieldError(
            "phone",
            "Phone must start with 05 followed by 8 digits",
        );
        isValid = false;
    }
    if (!email) {
        showFieldError("email", "Email is required");
        isValid = false;
    } else if (!isValidEmail(email)) {
        showFieldError("email", "Please enter a valid email");
        isValid = false;
    }
    if (!password) {
        showFieldError("password", "Password is required");
        isValid = false;
    } else if (!isPasswordValid(password)) {
        showFieldError("password", "Password must meet all requirements");
        isValid = false;
    }
    if (!passwordConfirm) {
        showFieldError("passwordConfirm", "Please confirm your password");
        isValid = false;
    } else if (password !== passwordConfirm) {
        showFieldError("passwordConfirm", "Passwords do not match");
        isValid = false;
    }

    return isValid;
}

/**
 * Show Login Error Alert
 */
function showLoginError(message) {
    const errorDiv = document.getElementById("login-error");
    if (errorDiv) {
        errorDiv.textContent = message;
        errorDiv.classList.add("active");
    }
}

/**
 * Show Register Error Alert
 */
function showRegisterError(message) {
    const errorDiv = document.getElementById("register-error");
    if (errorDiv) {
        errorDiv.textContent = message;
        errorDiv.classList.add("active");
    }
}

/**
 * Show Field-Level Error
 */
function showFieldError(fieldName, message) {
    const errorElement = document.getElementById(`${fieldName}-error`);
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.classList.add("active");
    }
}

/**
 * Clear Field-Level Error
 */
function clearFieldError(fieldName) {
    const errorElement = document.getElementById(`${fieldName}-error`);
    if (errorElement) {
        errorElement.textContent = "";
        errorElement.classList.remove("active");
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
    const requirementsBox = document.getElementById("password-requirements");
    if (!password) {
        requirementsBox.classList.remove("active");
        return;
    }

    requirementsBox.classList.add("active");

    const requirements = {
        length: password.length >= 8,
        uppercase: /[A-Z]/.test(password),
        lowercase: /[a-z]/.test(password),
        number: /[0-9]/.test(password),
        special: /[!@#$%^&*]/.test(password),
    };

    // Update requirement items
    updateRequirement("req-length", requirements.length);
    updateRequirement("req-uppercase", requirements.uppercase);
    updateRequirement("req-lowercase", requirements.lowercase);
    updateRequirement("req-number", requirements.number);
    updateRequirement("req-special", requirements.special);
}

/**
 * Update Requirement Item Visual State
 */
function updateRequirement(elementId, isMet) {
    const element = document.getElementById(elementId);
    if (element) {
        if (isMet) {
            element.classList.add("met");
        } else {
            element.classList.remove("met");
        }
    }
}

/**
 * Check if Password Meets All Requirements
 */
function isPasswordValid(password) {
    return (
        password.length >= 8 &&
        /[A-Z]/.test(password) &&
        /[a-z]/.test(password) &&
        /[0-9]/.test(password) &&
        /[!@#$%^&*]/.test(password)
    );
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
    return sessionStorage.getItem("auth_token") || localStorage.getItem("auth_token");
}

/**
 * Get Stored User Role
 */
function getUserRole() {
    return sessionStorage.getItem("user_role") || localStorage.getItem("user_role");
}

/**
 * Get Stored User Data
 */
function getUserData() {
    const data =
        sessionStorage.getItem("user_data") || localStorage.getItem("user_data");
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
    sessionStorage.removeItem("auth_token");
    sessionStorage.removeItem("user_role");
    sessionStorage.removeItem("user_type");
    sessionStorage.removeItem("user_data");

    // Clear legacy keys too
    localStorage.removeItem("auth_token");
    localStorage.removeItem("user_role");
    localStorage.removeItem("user_type");
    localStorage.removeItem("user_data");
    window.location.href = "/login";
}
