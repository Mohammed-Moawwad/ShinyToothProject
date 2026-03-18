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

    .auth-wrapper {
        display: flex;
        min-height: 100vh;
        background: #fff;
    }

    /* Left Side - Branding */
    .auth-branding {
        flex: 1;
        background: linear-gradient(135deg, #002050 0%, #003263 45%, #047a6e 80%, #059386 100%);
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
        align-items: center;
        justify-content: center;
        padding: 40px;
    }

    .auth-form-container {
        width: 100%;
        max-width: 420px;
    }

    .form-header {
        margin-bottom: 2.5rem;
    }

    .form-header h1 {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--dark-blue);
        margin: 0 0 0.5rem 0;
    }

    .form-header p {
        color: #6b7280;
        font-size: 0.95rem;
        margin: 0;
    }

    .form-group {
        margin-bottom: 1.3rem;
    }

    .form-label {
        display: block;
        color: var(--dark-blue);
        font-weight: 600;
        margin-bottom: 0.6rem;
        font-size: 0.9rem;
    }

    .form-control {
        width: 100%;
        border: 1.5px solid #e0e7ff;
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 0.95rem;
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

    .form-check {
        display: flex;
        align-items: center;
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
    }

    .form-check-input:checked {
        background-color: var(--teal);
        border-color: var(--teal);
    }

    .form-check-label {
        color: #4b5563;
        font-size: 0.9rem;
        cursor: pointer;
    }

    .btn-auth {
        width: 100%;
        background: linear-gradient(90deg, var(--dark-blue) 0%, var(--teal) 100%);
        border: none;
        color: #fff;
        font-weight: 700;
        padding: 12px 24px;
        border-radius: 10px;
        font-size: 1rem;
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
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #d0d5dd;
        text-align: center;
    }

    .footer-text {
        color: var(--dark-blue);
        font-size: 0.9rem;
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

    .forgot-link {
        display: inline-block;
        margin-top: 0.5rem;
        font-size: 0.85rem;
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
        }
    }
</style>

<div class="auth-wrapper">
    <!-- Left Side - Branding -->
    <div class="auth-branding">
        <div class="auth-circle auth-circle-1"></div>
        <div class="auth-circle auth-circle-2"></div>
        
        <div class="branding-content">
            <div class="branding-header">
                <div class="branding-logo">
                    <img src="{{ asset('images/logo.png') }}" alt="ShinyTooth Logo">
                </div>
                <h1 class="branding-title">ShinyTooth</h1>
            </div>
        </div>
    </div>

    <!-- Right Side - Form -->
    <div class="auth-form-section">
        <div class="auth-form-container">
            <div class="form-header">
                <h1>Welcome Back</h1>
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
                    <input 
                        type="password" 
                        class="form-control" 
                        id="password" 
                        name="password" 
                        placeholder="••••••••" 
                        required
                    >
                    <small class="error-text" id="password-error"></small>
                </div>

                <div class="form-check">
                    <input 
                        class="form-check-input" 
                        type="checkbox" 
                        id="remember" 
                        name="remember"
                    >
                    <label class="form-check-label" for="remember">
                        Remember me for 30 days
                    </label>
                </div>

                <button type="submit" class="btn-auth">Sign In</button>
            </form>

            <div class="form-footer">
                <a href="#" class="auth-link forgot-link">Forgot your password?</a>
                <p class="footer-text">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="auth-link">Create one</a>
                </p>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/auth.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        setupLoginForm();
    });
</script>
@endsection



