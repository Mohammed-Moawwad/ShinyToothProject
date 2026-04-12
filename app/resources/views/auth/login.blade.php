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

    .login-page {
        min-height: 100vh;
        background: linear-gradient(150deg, #002a55 0%, #003263 35%, #036b61 70%, #059386 100%);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
    }

    /* Top branding */
    .login-brand {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 2rem;
    }

    .login-brand img {
        height: 80px;
        width: auto;
        filter: drop-shadow(0 2px 8px rgba(0,50,99,.15));
        margin-bottom: 0.6rem;
    }

    .login-brand-name {
        font-size: 2rem;
        font-weight: 800;
        color: #ffffff;
        margin: 0;
        letter-spacing: -0.5px;
        text-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }

    .login-brand-tagline {
        font-size: 0.85rem;
        color: rgba(255,255,255,0.75);
        margin: 0.2rem 0 0 0;
        font-weight: 500;
    }

    /* Login card */
    .login-card {
        background: linear-gradient(135deg, #ffffff 0%, #f5fbfb 100%);
        border-radius: 18px;
        box-shadow: 0 10px 50px rgba(0, 50, 99, 0.15), 0 0 1px rgba(5, 147, 134, 0.2);
        border: 1px solid rgba(5, 147, 134, 0.15);
        padding: 36px 40px;
        width: 100%;
        max-width: 380px;
        backdrop-filter: blur(10px);
    }

    .form-header {
        text-align: center;
        margin-bottom: 1.8rem;
    }

    .form-header h2 {
        font-size: 1.35rem;
        font-weight: 700;
        color: var(--dark-blue);
        margin: 0 0 0.3rem 0;
    }

    .form-header p {
        color: #6b7280;
        font-size: 0.85rem;
        margin: 0;
    }

    .form-group {
        margin-bottom: 1.1rem;
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

    .password-wrapper {
        position: relative;
    }

    .password-wrapper .form-control {
        padding-right: 42px;
    }

    .password-toggle {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        color: #9ca3af;
        padding: 0;
        font-size: 1rem;
        line-height: 1;
        transition: color 0.2s;
    }

    .password-toggle:hover {
        color: var(--teal);
    }

    .error-text {
        color: #dc2626;
        font-size: 0.78rem;
        margin-top: 0.3rem;
        display: none;
    }

    .error-text.active {
        display: block;
    }

    .remember-forgot {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.3rem;
    }

    .form-check {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0;
    }

    .form-check-input {
        width: 16px;
        height: 16px;
        border: 1.5px solid #d1d5db;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s ease;
        accent-color: var(--teal);
    }

    .form-check-label {
        color: #4b5563;
        font-size: 0.83rem;
        cursor: pointer;
    }

    .forgot-link {
        font-size: 0.83rem;
        color: var(--teal);
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s;
    }

    .forgot-link:hover {
        color: var(--dark-blue);
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
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }

    .alert-danger {
        background: #fee2e2;
        border: 1px solid #fca5a5;
        color: #b91c1c;
        border-radius: 9px;
        padding: 0.8rem 1rem;
        font-weight: 500;
        font-size: 0.875rem;
        margin-bottom: 1.2rem;
        display: none;
    }

    .alert-danger.active {
        display: block;
    }

    .form-footer {
        margin-top: 1.3rem;
        padding-top: 1.2rem;
        border-top: 1px solid #e5e7eb;
        text-align: center;
    }

    .footer-text {
        color: #6b7280;
        font-size: 0.85rem;
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

    @media (max-width: 480px) {
        .login-card {
            padding: 28px 24px;
        }

        .login-brand-name {
            font-size: 1.6rem;
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

    <div class="login-page">

    <!-- Brand at top -->
    <div class="login-brand">
        <img src="{{ asset('images/logo.png') }}" alt="ShinyTooth Logo">
        <h1 class="login-brand-name">ShinyTooth</h1>
    </div>

    <!-- Login card -->
    <div class="login-card">
        <div class="form-header">
            <h2>Welcome Back</h2>
            <p>Sign in to your account</p>
        </div>

        <div id="login-error" class="alert-danger"></div>

        <form id="login-form">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input 
                    type="email" 
                    class="form-control" 
                    id="email" 
                    name="email" 
                    placeholder="your@email.com" 
                    required
                >
                <small class="error-text" id="email-error"></small>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div class="password-wrapper">
                    <input 
                        type="password" 
                        class="form-control" 
                        id="password" 
                        name="password" 
                        placeholder="••••••••" 
                        required
                    >
                    <button type="button" class="password-toggle" id="login-password-toggle" aria-label="Toggle password visibility">
                        <i class="bi bi-eye" id="login-password-icon"></i>
                    </button>
                </div>
                <small class="error-text" id="password-error"></small>
            </div>

            <div class="remember-forgot">
                <div class="form-check">
                    <input 
                        class="form-check-input" 
                        type="checkbox" 
                        id="remember" 
                        name="remember"
                    >
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
                <a href="#" class="forgot-link">Forgot password?</a>
            </div>

            <button type="submit" class="btn-auth">Sign In</button>
        </form>
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
                <a href="/register" style="color: rgba(255,255,255,.75); text-decoration: none; display: block; margin-bottom: 8px; transition: color .2s;">Sign Up</a>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ── Login Form Handler ──────────────────────────────────────────
        const form = document.getElementById('login-form');
        if (form) {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                const errorBox = document.getElementById('login-error');
                if (errorBox) { errorBox.style.display = 'none'; errorBox.textContent = ''; }

                const email    = document.getElementById('email').value;
                const password = document.getElementById('password').value;
                const csrfToken = document.querySelector('meta[name="csrf-token"]');

                try {
                    const response = await fetch('/login', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken ? csrfToken.content : '',
                        },
                        body: JSON.stringify({ email, password }),
                    });

                    const data = await response.json();

                    if (response.ok) {
                        localStorage.setItem('auth_token', data.token);
                        localStorage.setItem('user_type', data.user_type);
                        const redirect = data.redirect || (data.user_type === 'dentist' ? '/doctor/dashboard' : '/patient/dashboard');
                        window.location.href = redirect;
                    } else {
                        const msg = data.message || (data.errors && data.errors.email ? data.errors.email[0] : 'Invalid credentials.');
                        if (errorBox) { errorBox.textContent = msg; errorBox.style.display = 'block'; }
                    }
                } catch (err) {
                    console.error('Login error:', err);
                    if (errorBox) { errorBox.textContent = 'An error occurred. Please try again.'; errorBox.style.display = 'block'; }
                }
            });
        }

        // ── Password show/hide toggle ───────────────────────────────────
        const toggleBtn   = document.getElementById('login-password-toggle');
        const passwordInput = document.getElementById('password');
        const passwordIcon  = document.getElementById('login-password-icon');

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                const isPassword = passwordInput.type === 'password';
                passwordInput.type = isPassword ? 'text' : 'password';
                passwordIcon.classList.toggle('bi-eye', !isPassword);
                passwordIcon.classList.toggle('bi-eye-slash', isPassword);
            });
        }
    });
</script>
@endsection



