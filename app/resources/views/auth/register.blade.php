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
        padding: 50px 40px;
        overflow-y: auto;
        max-height: 100vh;
    }

    .auth-form-container {
        width: 100%;
        max-width: 700px;
    }

    .form-header {
        margin-bottom: 3rem;
    }

    .form-header h1 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--dark-blue);
        margin: 0 0 0.8rem 0;
    }

    .form-header p {
        color: #6b7280;
        font-size: 1rem;
        margin: 0;
    }

    .form-group {
        margin-bottom: 1.6rem;
    }

    .form-label {
        display: block;
        color: var(--dark-blue);
        font-weight: 600;
        margin-bottom: 0.8rem;
        font-size: 0.95rem;
    }

    .form-control {
        width: 100%;
        border: 1.5px solid #e0e7ff;
        border-radius: 10px;
        padding: 14px 18px;
        font-size: 0.98rem;
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
        padding: 14px 28px;
        border-radius: 10px;
        font-size: 1.05rem;
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
        margin-top: 2rem;
        padding-top: 1.8rem;
        border-top: 1px solid #d0d5dd;
        text-align: center;
    }

    .footer-text {
        color: var(--dark-blue);
        font-size: 0.95rem;
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
        position: relative;
    }

    .password-toggle {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #6b7280;
        font-size: 1.1rem;
        transition: color 0.2s ease;
        background: none;
        border: none;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
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
                <h1>Join us today</h1>
                <p>Create your account</p>
            </div>

            <div id="register-error" class="alert-danger"></div>

            <form id="register-form">
                @csrf

                <div class="form-group">
                    <label for="first_name" class="form-label">First Name</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="first_name" 
                        name="first_name" 
                        placeholder="John" 
                        required
                    >
                    <small class="error-text" id="first_name-error"></small>
                </div>

                <div class="form-group">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="last_name" 
                        name="last_name" 
                        placeholder="Doe" 
                        required
                    >
                    <small class="error-text" id="last_name-error"></small>
                </div>

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
                    <label for="phone" class="form-label">Phone Number</label>
                    <input 
                        type="tel" 
                        class="form-control" 
                        id="phone" 
                        name="phone" 
                        placeholder="05X XXXX XXXX" 
                        pattern="05[0-9]{8}"
                        maxlength="10"
                        inputmode="numeric"
                        required
                    >
                    <small style="color: #059386; font-size: 0.8rem; margin-top: 0.3rem; display: block;">Only 10 numbers allowed (e.g: 0512345678)</small>
                    <small class="error-text" id="phone-error">Phone must start with 05 followed by 8 digits</small>
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
                        <button type="button" class="password-toggle" id="password-toggle">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <div class="password-requirements" id="password-requirements">
                        <div class="requirement-item" id="req-length">
                            <span class="requirement-icon">✓</span>
                            <span>At least 8 characters</span>
                        </div>
                        <div class="requirement-item" id="req-uppercase">
                            <span class="requirement-icon">✓</span>
                            <span>One uppercase letter (A-Z)</span>
                        </div>
                        <div class="requirement-item" id="req-lowercase">
                            <span class="requirement-icon">✓</span>
                            <span>One lowercase letter (a-z)</span>
                        </div>
                        <div class="requirement-item" id="req-number">
                            <span class="requirement-icon">✓</span>
                            <span>One number (0-9)</span>
                        </div>
                        <div class="requirement-item" id="req-special">
                            <span class="requirement-icon">✓</span>
                            <span>One special character (!@#$%^&*)</span>
                        </div>
                    </div>
                    <small class="error-text" id="password-error"></small>
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input 
                        type="password" 
                        class="form-control" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        placeholder="••••••••" 
                        required
                    >
                    <small class="error-text" id="passwordConfirm-error"></small>
                </div>

                <button type="submit" id="register-btn" class="btn-auth">Create Account</button>
            </form>

            <div class="form-footer">
                <p class="footer-text">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="auth-link">Sign in</a>
                </p>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/auth.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        setupRegisterForm();
    });
</script>
@endsection
