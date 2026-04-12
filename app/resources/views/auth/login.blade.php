@extends('layouts.app')

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

<script src="{{ asset('js/auth.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        setupLoginForm();

        // Password show/hide toggle
        const toggleBtn = document.getElementById('login-password-toggle');
        const passwordInput = document.getElementById('password');
        const passwordIcon = document.getElementById('login-password-icon');

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



