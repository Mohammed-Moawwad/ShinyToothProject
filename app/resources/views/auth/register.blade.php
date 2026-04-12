@extends('layouts.auth')

@section('content')
<style>
    :root {
        --teal:      #059386;
        --dark-blue: #003263;
    }

    /* Top branding */
    .register-brand {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 2rem;
    }

    .register-brand img {
        height: 80px;
        width: auto;
        filter: drop-shadow(0 2px 8px rgba(0,50,99,.15));
        margin-bottom: 0.6rem;
    }

    .register-brand-name {
        font-size: 2rem;
        font-weight: 800;
        color: #ffffff;
        margin: 0;
        letter-spacing: -0.5px;
        text-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }

    /* Register card */
    .register-card {
        background: linear-gradient(135deg, #ffffff 0%, #f5fbfb 100%);
        border-radius: 18px;
        box-shadow: 0 10px 50px rgba(0, 50, 99, 0.15), 0 0 1px rgba(5, 147, 134, 0.2);
        border: 1px solid rgba(5, 147, 134, 0.15);
        padding: 36px 40px;
        width: 100%;
        max-width: 420px;
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

    .form-check {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1.3rem;
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
        margin: 0;
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
        .register-card {
            padding: 28px 24px;
        }

        .register-brand-name {
            font-size: 1.6rem;
        }
    }
</style>

<!-- Brand at top -->
<div class="register-brand">
    <img src="{{ asset('images/logo.png') }}" alt="ShinyTooth Logo">
    <h1 class="register-brand-name">ShinyTooth</h1>
</div>

<!-- Register card -->
<div class="register-card">
    <div class="form-header">
        <h2>Create Account</h2>
        <p>Join ShinyTooth Dental Clinic</p>
    </div>

    <form id="register-form" method="POST" action="/register">
        @csrf

        <div class="form-group">
            <label for="name" class="form-label">Full Name</label>
            <input 
                type="text" 
                class="form-control @error('name') is-invalid @enderror" 
                id="name" 
                name="name" 
                value="{{ old('name') }}"
                placeholder="John Doe" 
                required
            >
            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Email Address</label>
            <input 
                type="email" 
                class="form-control @error('email') is-invalid @enderror" 
                id="email" 
                name="email" 
                value="{{ old('email') }}"
                placeholder="your@email.com" 
                required
            >
            @error('email')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <div class="password-wrapper">
                <input 
                    type="password" 
                    class="form-control @error('password') is-invalid @enderror" 
                    id="password" 
                    name="password" 
                    placeholder="••••••••" 
                    required
                >
                <button type="button" class="password-toggle" id="register-password-toggle" aria-label="Toggle password visibility">
                    <i class="bi bi-eye" id="register-password-icon"></i>
                </button>
            </div>
            @error('password')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="password-confirm" class="form-label">Confirm Password</label>
            <div class="password-wrapper">
                <input 
                    type="password" 
                    class="form-control" 
                    id="password-confirm" 
                    name="password_confirmation" 
                    placeholder="••••••••" 
                    required
                >
                <button type="button" class="password-toggle" id="register-password-confirm-toggle" aria-label="Toggle password visibility">
                    <i class="bi bi-eye" id="register-password-confirm-icon"></i>
                </button>
            </div>
        </div>

        <div class="form-check">
            <input 
                class="form-check-input" 
                type="checkbox" 
                id="terms" 
                name="terms"
                required
            >
            <label class="form-check-label" for="terms">
                I agree to the <a href="#" class="auth-link">Terms of Service</a> and <a href="#" class="auth-link">Privacy Policy</a>
            </label>
        </div>

        <button type="submit" class="btn-auth">Create Account</button>
    </form>

    <div class="form-footer">
        <p class="footer-text">Already have an account? <a href="/login" class="auth-link">Sign In</a></p>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Password toggle 1
        const toggleBtn1 = document.getElementById('register-password-toggle');
        const passwordInput1 = document.getElementById('password');
        const passwordIcon1 = document.getElementById('register-password-icon');

        if (toggleBtn1) {
            toggleBtn1.addEventListener('click', function() {
                const isPassword = passwordInput1.type === 'password';
                passwordInput1.type = isPassword ? 'text' : 'password';
                passwordIcon1.classList.toggle('bi-eye', !isPassword);
                passwordIcon1.classList.toggle('bi-eye-slash', isPassword);
            });
        }

        // Password toggle 2
        const toggleBtn2 = document.getElementById('register-password-confirm-toggle');
        const passwordInput2 = document.getElementById('password-confirm');
        const passwordIcon2 = document.getElementById('register-password-confirm-icon');

        if (toggleBtn2) {
            toggleBtn2.addEventListener('click', function() {
                const isPassword = passwordInput2.type === 'password';
                passwordInput2.type = isPassword ? 'text' : 'password';
                passwordIcon2.classList.toggle('bi-eye', !isPassword);
                passwordIcon2.classList.toggle('bi-eye-slash', isPassword);
            });
        }
    });
</script>
@endsection
