@extends('layouts.auth')

@section('content')
<style>
    :root {
        --teal:      #059386;
        --dark-blue: #003263;
    }

    body {
        margin: 0;
        padding: 0;
    }

    /* Navbar */
    .main-nav {
        background: linear-gradient(90deg, #003263 0%, #059386 100%);
        padding: 14px 0;
        position: sticky;
        top: 0;
        z-index: 1050;
        box-shadow: 0 2px 10px rgba(0,0,0,.15);
    }
    .nav-link-custom {
        color: rgba(255,255,255,.88) !important;
        font-weight: 500;
        padding: 6px 14px;
        border-radius: 8px;
        transition: background .2s, color .2s;
        text-decoration: none;
    }
    .nav-link-custom:hover {
        color: #fff !important;
        background: rgba(255,255,255,.12);
    }
    .btn-nav-login {
        border: 2px solid rgba(255,255,255,.75);
        color: #fff;
        border-radius: 25px;
        padding: 8px 22px;
        font-weight: 600;
        background: transparent;
        text-decoration: none;
        transition: all .2s;
        font-size: .95rem;
    }
    .btn-nav-login:hover {
        background: rgba(255,255,255,.15);
        color: #fff;
    }
    .btn-nav-signup {
        background: #fff;
        color: var(--dark-blue) !important;
        border-radius: 25px;
        padding: 8px 22px;
        font-weight: 700;
        text-decoration: none;
        transition: all .2s;
        font-size: .95rem;
    }
    .btn-nav-signup:hover {
        background: #dff6f3;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,.15);
    }

    .auth-wrapper {
        display: flex;
        min-height: 100vh;
        background: #fff;
    }

    /* Left Side - Branding */
    .auth-branding {
        flex: 1;
        background: transparent;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        padding: 40px;
        color: #fff;
    }

    .auth-circle {
        position: absolute;
        background: rgba(255,255,255,.04);
        border-radius: 50%;
        pointer-events: none;
    }

    .auth-circle-1 {
        width: 400px;
        height: 400px;
        top: -10%;
        right: -10%;
    }

    .auth-circle-2 {
        width: 300px;
        height: 300px;
        bottom: -5%;
        left: -5%;
    }

    /* Image Carousel */
    .auth-carousel {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 0;
        overflow: hidden;
    }

    .carousel-item {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        opacity: 0;
        transition: opacity 1s ease-in-out;
    }

    .carousel-item.active {
        opacity: 1;
    }

    /* Overlay to darken images for better text visibility */
    .auth-carousel-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(0, 32, 80, 0.4) 0%, rgba(0, 50, 99, 0.4) 45%, rgba(3, 107, 97, 0.4) 80%, rgba(5, 147, 134, 0.4) 100%);
        z-index: 1;
        pointer-events: none;
    }

    .branding-content {
        position: relative;
        z-index: 2;
        text-align: center;
    }

    .branding-header {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        justify-content: center;
    }

    .branding-logo img {
        height: 140px;
        width: auto;
        background: transparent;
        mix-blend-mode: multiply;
        filter: drop-shadow(0 2px 8px rgba(0,0,0,.1));
    }

    .branding-title {
        font-size: 2.8rem;
        font-weight: 800;
        margin: 0;
        line-height: 1;
        color: #fff;
    }

    /* Right Side - Form */
    .auth-form-section {
        flex: 1;
        display: flex;
        align-items: flex-start;
        justify-content: center;
        padding: 60px 24px;
        overflow-y: auto;
        min-height: 100vh;
    }

    .auth-form-container {
        width: 100%;
        max-width: 480px;
    }

    .form-header {
        margin-bottom: 1.5rem;
    }

    .form-header h1 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dark-blue);
        margin: 0 0 0.4rem 0;
    }

    .form-header p {
        color: #6b7280;
        font-size: 0.88rem;
        margin: 0;
    }

    .form-header p {
        color: #6b7280;
        font-size: 1rem;
        margin: 0;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-label {
        display: block;
        color: var(--dark-blue);
        font-weight: 600;
        margin-bottom: 0.45rem;
        font-size: 0.85rem;
    }

    .form-control {
        width: 100%;
        border: 1.5px solid #e0e7ff;
        border-radius: 9px;
        padding: 10px 14px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        background: #f8fafc;
        box-sizing: border-box;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--teal);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(5, 147, 134, 0.1);
    }

    .error-text {
        color: #dc2626;
        font-size: 0.8rem;
        margin-top: 0.3rem;
        display: none;
    }

    .error-text.active {
        display: block;
    }

    /* Radio checklist for gender & blood type */
    .radio-group {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 4px;
    }

    .radio-option {
        display: none;
    }

    .radio-label {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 6px 14px;
        border: 1.5px solid #e0e7ff;
        border-radius: 20px;
        font-size: 0.82rem;
        cursor: pointer;
        background: #f8fafc;
        color: #374151;
        transition: all 0.2s ease;
        user-select: none;
        white-space: nowrap;
    }

    .radio-option:checked + .radio-label {
        background: var(--teal);
        border-color: var(--teal);
        color: #fff;
        font-weight: 600;
    }

    .radio-label:hover {
        border-color: var(--teal);
        color: var(--teal);
    }

    .radio-option:checked + .radio-label:hover {
        color: #fff;
    }

    .form-check {
        display: flex;
        align-items: flex-start;
        gap: 0.6rem;
        margin-bottom: 1.3rem;
    }

    .form-check-input {
        width: 18px;
        height: 18px;
        border: 1.5px solid #d1d5db;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-top: 2px;
        flex-shrink: 0;
    }

    .form-check-input:checked {
        background-color: var(--teal);
        border-color: var(--teal);
    }

    .form-check-label {
        color: #4b5563;
        font-size: 0.9rem;
        cursor: pointer;
        line-height: 1.5;
    }

    .btn-auth {
        width: 100%;
        background: linear-gradient(90deg, var(--dark-blue) 0%, var(--teal) 100%);
        border: none;
        color: #fff;
        font-weight: 700;
        padding: 11px 24px;
        border-radius: 9px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        cursor: pointer;
        box-shadow: 0 4px 16px rgba(5, 147, 134, 0.25);
    }

    .btn-auth:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(5, 147, 134, 0.35);
    }

    .btn-auth:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        background: linear-gradient(90deg, #999999 0%, #666666 100%) !important;
        box-shadow: none !important;
        transform: none !important;
    }

    .alert-danger {
        background: #fee2e2;
        border: 1px solid #fca5a5;
        color: #b91c1c;
        border-radius: 10px;
        padding: 1rem;
        font-weight: 500;
        margin-bottom: 1.5rem;
        display: none;
    }

    .alert-danger.active {
        display: block;
    }

    .form-footer {
        margin-top: 1.2rem;
        padding-top: 1.2rem;
        border-top: 1px solid #d0d5dd;
        text-align: center;
    }

    .footer-text {
        color: var(--dark-blue);
        font-size: 0.88rem;
        margin: 0;
    }

    .auth-link {
        color: var(--teal);
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s ease;
    }

    .auth-link:hover {
        color: var(--dark-blue);
    }

    /* Password Visibility Toggle */
    .password-wrapper {
        display: flex;
        border: 1.5px solid #e0e7ff;
        border-radius: 9px;
        overflow: hidden;
        background: #f8fafc;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .password-wrapper:focus-within {
        border-color: var(--teal);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(5, 147, 134, 0.1);
    }

    .password-wrapper .form-control {
        border: none !important;
        background: transparent !important;
        box-shadow: none !important;
        border-radius: 0 !important;
        flex: 1;
        min-width: 0;
    }

    .password-wrapper .form-control:focus {
        outline: none;
        box-shadow: none !important;
        border: none !important;
    }

    .password-toggle {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        flex-shrink: 0;
        cursor: pointer;
        color: #6b7280;
        font-size: 1.1rem;
        transition: color 0.2s ease;
        background: transparent;
        border: none;
        padding: 0;
    }

    .password-toggle:hover {
        color: var(--teal);
    }

    /* Password Validation Checklist */
    .password-requirements {
        background: #f8fafc;
        border-left: 3px solid #e0e7ff;
        padding: 12px 14px;
        border-radius: 6px;
        margin-top: 0.8rem;
        display: none;
    }

    .password-requirements.active {
        display: block;
    }

    .requirement-item {
        font-size: 0.85rem;
        color: #6b7280;
        margin: 6px 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .requirement-item.met {
        color: #059386;
    }

    .requirement-icon {
        width: 16px;
        height: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
    }

    .requirement-item.met .requirement-icon {
        background: #059386;
        color: white;
        border-radius: 50%;
    }

    .requirement-item:not(.met) .requirement-icon {
        border: 1px solid #d1d5db;
        border-radius: 50%;
        color: #d1d5db;
    }

    @media (max-width: 768px) {
        .auth-wrapper {
            flex-direction: column;
        }

        .auth-branding {
            min-height: 300px;
            padding: 30px 20px;
        }

        .branding-title {
            font-size: 2rem;
        }

        .auth-form-section {
            padding: 30px 20px;
            max-height: none;
        }
    }
</style>

<nav class="main-nav" id="mainNav">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <a href="/" class="d-flex align-items-center gap-2 text-decoration-none">
                <img src="{{ asset('images/logo.png') }}" alt="ShinyTooth Logo" height="60"
                     style="border-radius:8px; object-fit:contain;">
                <span style="color:#fff; font-size:1.2rem; font-weight:800; letter-spacing:-.3px;">ShinyTooth</span>
            </a>
            <div class="d-none d-md-flex align-items-center gap-1">
                <a href="/#services"   class="nav-link-custom">Services</a>
                <a href="/#doctors"    class="nav-link-custom">Doctors</a>
                <a href="/#who-we-are" class="nav-link-custom">Who are we</a>
                <a href="/#contact"    class="nav-link-custom">Contact us</a>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="/login"    class="btn-nav-login">Login</a>
                <a href="/register" class="btn-nav-signup">Sign Up</a>
            </div>
        </div>
    </div>
</nav>

<div class="auth-wrapper">
    <!-- Left Side - Branding with Image Carousel -->
    <div class="auth-branding">
        <!-- Background Image Carousel -->
        <div class="auth-carousel" id="auth-carousel">
            <div class="carousel-item active" style="background-image: url('{{ asset('images/SignUpImages/image1.jpg') }}');"></div>
            <div class="carousel-item" style="background-image: url('{{ asset('images/SignUpImages/image2.jpg') }}');"></div>
            <div class="carousel-item" style="background-image: url('{{ asset('images/SignUpImages/image3.jpg') }}');"></div>
            <div class="carousel-item" style="background-image: url('{{ asset('images/SignUpImages/image4.jpg') }}');"></div>
            <div class="carousel-item" style="background-image: url('{{ asset('images/SignUpImages/image5.jpg') }}');"></div>
            <div class="carousel-item" style="background-image: url('{{ asset('images/SignUpImages/image6.jpg') }}');"></div>
            <div class="carousel-item" style="background-image: url('{{ asset('images/SignUpImages/image7.jpg') }}');"></div>
            <div class="carousel-item" style="background-image: url('{{ asset('images/SignUpImages/image8.jpg') }}');"></div>
        </div>
        
        <!-- Overlay -->
        <div class="auth-carousel-overlay"></div>
        
        <div class="auth-circle auth-circle-1"></div>
        <div class="auth-circle auth-circle-2"></div>
        
        <div class="branding-content">
            <div class="branding-header">
                <div class="branding-logo">
                    <img src="{{ asset('images/logo.png') }}" alt="ShinyTooth Logo">
                </div>
                <p class="branding-title">ShinyTooth</p>
            </div>
        </div>
    </div>

    <!-- Right Side - Form -->
    <div class="auth-form-section">
        <div class="auth-form-container">
            <div class="form-header">
                <h1>Join us today</h1>
                <p>Create your account</p>
            </div>

            <div id="register-error" class="alert-danger"></div>

            <form id="register-form">
                @csrf

                <!-- Full Name (Single Field) -->
                <div class="form-group">
                    <label for="full_name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="full_name" name="full_name" placeholder="John Doe" required>
                    <small class="error-text" id="full_name-error"></small>
                </div>

                <!-- Phone Number -->
                <div class="form-group">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="05X XXXX XXXX" pattern="05[0-9]{8}" maxlength="10" inputmode="numeric" required>
                    <small style="color:#059386; font-size:0.8rem; margin-top:0.3rem; display:block;">Only 10 numbers (e.g: 0512345678)</small>
                    <small class="error-text" id="phone-error">Phone must start with 05 followed by 8 digits</small>
                </div>

                <!-- Nationality -->
                <div class="form-group">
                    <label for="nationality" class="form-label">Nationality</label>
                    <input type="text" class="form-control" id="nationality" name="nationality" placeholder="e.g. Saudi" required>
                    <small class="error-text" id="nationality-error"></small>
                </div>

                <!-- Gender -->
                <div class="form-group">
                    <label class="form-label">Gender</label>
                    <div class="radio-group" id="gender-group">
                        <input type="radio" class="radio-option" name="gender" id="gender-male" value="male">
                        <label class="radio-label" for="gender-male">Male</label>
                        <input type="radio" class="radio-option" name="gender" id="gender-female" value="female">
                        <label class="radio-label" for="gender-female">Female</label>
                    </div>
                    <small class="error-text" id="gender-error"></small>
                </div>

                <!-- Place of Birth -->
                <div class="form-group">
                    <label for="place_of_birth" class="form-label">Place of Birth</label>
                    <input type="text" class="form-control" id="place_of_birth" name="place_of_birth" placeholder="City, Country" required>
                    <small class="error-text" id="place_of_birth-error"></small>
                </div>

                <!-- Date of Birth -->
                <div class="form-group">
                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" required>
                    <small class="error-text" id="date_of_birth-error"></small>
                </div>

                <!-- Blood Type -->
                <div class="form-group">
                    <label class="form-label">Blood Type</label>
                    <div class="radio-group" id="blood_type-group">
                        <input type="radio" class="radio-option" name="blood_type" id="bt-ap" value="A+">
                        <label class="radio-label" for="bt-ap">A+</label>
                        <input type="radio" class="radio-option" name="blood_type" id="bt-am" value="A-">
                        <label class="radio-label" for="bt-am">A−</label>
                        <input type="radio" class="radio-option" name="blood_type" id="bt-bp" value="B+">
                        <label class="radio-label" for="bt-bp">B+</label>
                        <input type="radio" class="radio-option" name="blood_type" id="bt-bm" value="B-">
                        <label class="radio-label" for="bt-bm">B−</label>
                        <input type="radio" class="radio-option" name="blood_type" id="bt-abp" value="AB+">
                        <label class="radio-label" for="bt-abp">AB+</label>
                        <input type="radio" class="radio-option" name="blood_type" id="bt-abm" value="AB-">
                        <label class="radio-label" for="bt-abm">AB−</label>
                        <input type="radio" class="radio-option" name="blood_type" id="bt-op" value="O+">
                        <label class="radio-label" for="bt-op">O+</label>
                        <input type="radio" class="radio-option" name="blood_type" id="bt-om" value="O-">
                        <label class="radio-label" for="bt-om">O−</label>
                    </div>
                    <small class="error-text" id="blood_type-error"></small>
                </div>

                <!-- Address (Optional) -->
                <div class="form-group">
                    <label for="address" class="form-label">Address <span style="color:#999; font-size:0.8rem;">(Optional)</span></label>
                    <input type="text" class="form-control" id="address" name="address" placeholder="123 Main St, City">
                    <small class="error-text" id="address-error"></small>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="your@email.com" required>
                    <small class="error-text" id="email-error"></small>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="password-wrapper">
                        <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
                        <button type="button" class="password-toggle" id="password-toggle" tabindex="0">
                            <i class="bi bi-eye" id="password-eye-icon"></i>
                        </button>
                    </div>
                    <div class="password-requirements" id="password-requirements">
                        <div class="requirement-item" id="req-length"><span class="requirement-icon">✓</span><span>At least 8 characters</span></div>
                        <div class="requirement-item" id="req-uppercase"><span class="requirement-icon">✓</span><span>One uppercase letter (A-Z)</span></div>
                        <div class="requirement-item" id="req-lowercase"><span class="requirement-icon">✓</span><span>One lowercase letter (a-z)</span></div>
                        <div class="requirement-item" id="req-number"><span class="requirement-icon">✓</span><span>One number (0-9)</span></div>
                        <div class="requirement-item" id="req-special"><span class="requirement-icon">✓</span><span>One special character (!@#$%^&*)</span></div>
                    </div>
                    <small class="error-text" id="password-error"></small>
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required>
                    <small class="error-text" id="passwordConfirm-error"></small>
                </div>

                <button type="submit" id="register-btn" class="btn-auth" disabled>Create Account</button>
            </form>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="main-footer" style="background: linear-gradient(135deg, #003263 0%, #047a6e 100%); color: #fff; margin-top: auto;">
    <div class="container" style="padding: 50px 0;">
        <div class="row g-4">
            {{-- Brand column --}}
            <div class="col-lg-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <img src="{{ asset('images/logo.png') }}" alt="ShinyTooth"
                         height="60" style="border-radius:8px; object-fit:contain;">
                    <span style="color: #fff; font-size: 1.2rem; font-weight: 800;">ShinyTooth</span>
                </div>
                <p style="color:rgba(255,255,255,.65); font-size:.88rem; line-height:1.75;">
                    Your trusted dental care partner. We combine world-class expertise with
                    a warm, welcoming environment — because every smile deserves the best.
                </p>
            </div>

            {{-- Quick Links --}}
            <div class="col-6 col-lg-2 offset-lg-1">
                <h6 style="color: #fff; font-size: .9rem; font-weight: 700; margin-bottom: 18px;">Quick Links</h6>
                <a href="/" style="color: rgba(255,255,255,.75); text-decoration: none; display: block; margin-bottom: 8px; transition: color .2s;">Home</a>
                <a href="#" style="color: rgba(255,255,255,.75); text-decoration: none; display: block; margin-bottom: 8px; transition: color .2s;">Services</a>
                <a href="/login" style="color: rgba(255,255,255,.75); text-decoration: none; display: block; margin-bottom: 8px; transition: color .2s;">Login</a>
            </div>

            {{-- Contact --}}
            <div class="col-lg-4">
                <h6 style="color: #fff; font-size: .9rem; font-weight: 700; margin-bottom: 18px;">Contact Us</h6>
                <div class="d-flex align-items-start gap-2 mb-3" style="color:rgba(255,255,255,.65); font-size:.87rem;">
                    <i class="bi bi-telephone-fill" style="color:#059386; flex-shrink:0; margin-top:2px;"></i>
                    <span>+1 (800) 744-6983</span>
                </div>
                <div class="d-flex align-items-start gap-2 mb-3" style="color:rgba(255,255,255,.65); font-size:.87rem;">
                    <i class="bi bi-envelope-fill" style="color:#059386; flex-shrink:0; margin-top:2px;"></i>
                    <span>hello@shinytooth.com</span>
                </div>
                <div class="d-flex align-items-start gap-2" style="color:rgba(255,255,255,.65); font-size:.87rem;">
                    <i class="bi bi-clock-fill" style="color:#059386; flex-shrink:0; margin-top:2px;"></i>
                    <span>Mon – Sat | 8:00 AM – 7:00 PM</span>
                </div>
            </div>
        </div>
        <hr style="border-color: rgba(255,255,255,.15); margin: 30px 0 20px;">
        <p style="text-align: center; color: rgba(255,255,255,.5); font-size: 0.85rem; margin: 0;">
            &copy; 2026 ShinyTooth Dental Clinic. All rights reserved.
        </p>
    </div>
</footer>

<script src="{{ asset('js/auth.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Image Carousel - pick a random image on load
        const carousel = document.getElementById('auth-carousel');
        if (carousel) {
            const items = carousel.querySelectorAll('.carousel-item');
            if (items.length > 0) {
                items.forEach(item => item.classList.remove('active'));
                const randomIndex = Math.floor(Math.random() * items.length);
                items[randomIndex].classList.add('active');
            }
        }

        setupRegisterForm();
    });
</script>
@endsection
