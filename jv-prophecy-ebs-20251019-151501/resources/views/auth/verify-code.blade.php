@extends('layouts.app')

@section('title', 'Enter Verification Code - JV Prophecy Manager')

@section('content')
<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: var(--space-lg);">
    <div style="width: 100%; max-width: 480px;">
        <!-- Verification Card -->
        <div class="intel-card" style="padding: var(--space-2xl); border-radius: var(--radius-xl); margin-bottom: var(--space-lg);">
            
            <!-- Header -->
            <div style="text-align: center; margin-bottom: var(--space-2xl);">
                <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #0284c7 0%, #075985 100%); border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg) auto;">
                    <i class="fas fa-shield-check" style="color: white; font-size: 2rem;"></i>
                </div>
                <h1 style="font-size: 1.75rem; font-weight: 700; color: #0f172a; margin: 0 0 0.75rem 0;">
                    Verify Your Email
                </h1>
                <p style="color: #64748b; font-size: 1rem; margin: 0;">
                    We've sent a 6-digit verification code to:
                </p>
                <div style="background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 12px; padding: 0.75rem 1rem; font-weight: 600; color: #0369a1; font-size: 0.95rem; margin: 0.75rem 0; word-break: break-all;">
                    {{ $email }}
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if (session('success'))
                <div style="padding: 1rem 1.25rem; border-radius: 12px; margin-bottom: 1.5rem; background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534;">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div style="padding: 1rem 1.25rem; border-radius: 12px; margin-bottom: 1.5rem; background: #fef2f2; border: 1px solid #fecaca; color: #dc2626;">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif

            @error('recaptcha')
            <div style="color: #ef4444; font-size: 0.875rem; margin-bottom: var(--space-lg); padding: var(--space-sm); background: #fef2f2; border: 1px solid #fecaca; border-radius: var(--radius-md);">
                <i class="fas fa-exclamation-triangle" style="margin-right: var(--space-xs);"></i>{{ $message }}
            </div>
            @enderror

            <!-- Verification Form -->
            <form method="POST" action="{{ route('verification.verify-code') }}" id="verificationForm">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">

                <!-- Code Input Container -->
                <div style="margin: 2rem 0; text-align: center;">
                    <label for="verification_code" style="display: block; font-weight: 600; color: #374151; margin-bottom: 1rem; font-size: 1rem;">
                        <i class="fas fa-key" style="margin-right: 0.5rem;"></i>
                        Enter Verification Code
                    </label>

                    <input 
                        type="text" 
                        id="verification_code" 
                        name="verification_code" 
                        value="{{ old('verification_code') }}"
                        style="width: 100%; max-width: 280px; height: 70px; border: 2px solid #e5e7eb; border-radius: 16px; text-align: center; font-size: 2rem; font-weight: 700; letter-spacing: 0.5rem; color: #0f172a; background: #ffffff; outline: none; @error('verification_code') border-color: #ef4444; @enderror"
                        placeholder="000000"
                        maxlength="6"
                        pattern="[0-9]{6}"
                        inputmode="numeric"
                        required
                        autofocus
                        autocomplete="off">
                        
                    @error('verification_code')
                        <div style="color: #dc2626; font-size: 0.875rem; margin-top: 0.5rem; font-weight: 500;">
                            <i class="fas fa-exclamation-triangle" style="margin-right: 0.5rem;"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" id="verifyButton" style="width: 100%; height: 56px; background: linear-gradient(135deg, #0284c7 0%, #075985 100%); border: none; border-radius: 16px; color: white; font-size: 1.1rem; font-weight: 600; cursor: pointer; margin: 1.5rem 0;">
                    <i class="fas fa-shield-check"></i>
                    <span>Verify Email</span>
                </button>
            </form>

            <!-- Resend Section -->
            <div style="text-align: center; padding: 1.5rem 0; border-top: 1px solid #e5e7eb; margin-top: 1.5rem;">
                <p style="color: #64748b; font-size: 0.9rem; margin-bottom: 1rem;">
                    Didn't receive the code? Check your spam folder or
                </p>
                <form method="POST" action="{{ route('verification.resend') }}" style="display: inline;" id="resendForm">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                    <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response-resend">
                    <button type="submit" id="resendButton" style="background: transparent; border: 2px solid #e5e7eb; border-radius: 12px; color: #374151; padding: 0.75rem 1.5rem; font-size: 0.9rem; font-weight: 500; cursor: pointer;">
                        <i class="fas fa-paper-plane"></i>
                        <span id="resendText">Resend Code</span>
                    </button>
                </form>
            </div>

            <!-- Help Section -->
            <div style="text-align: center; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #f1f5f9;">
                <p style="color: #94a3b8; font-size: 0.85rem; margin-bottom: 1rem;">
                    <i class="fas fa-info-circle" style="margin-right: 0.5rem;"></i>
                    Your verification code remains valid until you verify your account
                </p>
                <a href="{{ route('login') }}" style="color: #0284c7; text-decoration: none; font-size: 0.9rem; font-weight: 500;">
                    <i class="fas fa-arrow-left"></i>
                    Back to Login
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
@if(app(\App\Services\RecaptchaService::class)->isEnabled())
<script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}" async defer></script>
@endif
<script>
document.addEventListener('DOMContentLoaded', function() {
    const codeInput = document.getElementById('verification_code');
    const verificationForm = document.getElementById('verificationForm');
    const resendForm = document.getElementById('resendForm');
    const resendButton = document.getElementById('resendButton');
    const resendText = document.getElementById('resendText');

    // Simple number-only input formatting
    if (codeInput) {
        codeInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 6) {
                value = value.slice(0, 6);
            }
            e.target.value = value;
        });

        // Handle paste
        codeInput.addEventListener('paste', function(e) {
            e.preventDefault();
            const paste = (e.clipboardData || window.clipboardData).getData('text');
            const numbers = paste.replace(/\D/g, '').slice(0, 6);
            e.target.value = numbers;
        });
    }

    // Form submission with reCAPTCHA
    if (verificationForm) {
        verificationForm.addEventListener('submit', function(e) {
            const code = codeInput.value.trim();
            
            // Basic validation
            if (code.length !== 6 || !/^\d{6}$/.test(code)) {
                e.preventDefault();
                alert('Please enter a valid 6-digit verification code.');
                codeInput.focus();
                return false;
            }

            // Handle reCAPTCHA if enabled
            @if(app(\App\Services\RecaptchaService::class)->isEnabled())
            e.preventDefault();
            
            if (typeof grecaptcha !== 'undefined') {
                grecaptcha.ready(function() {
                    grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {action: 'verify_email'}).then(function(token) {
                        document.getElementById('g-recaptcha-response').value = token;
                        verificationForm.submit();
                    }).catch(function(error) {
                        console.error('reCAPTCHA error:', error);
                        verificationForm.submit();
                    });
                });
            } else {
                verificationForm.submit();
            }
            @endif
        });
    }

    // Resend form handler
    if (resendForm) {
        resendForm.addEventListener('submit', function(e) {
            @if(app(\App\Services\RecaptchaService::class)->isEnabled())
            e.preventDefault();
            
            resendButton.disabled = true;
            resendText.textContent = 'Sending...';
            
            if (typeof grecaptcha !== 'undefined') {
                grecaptcha.ready(function() {
                    grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {action: 'resend_email'}).then(function(token) {
                        document.getElementById('g-recaptcha-response-resend').value = token;
                        resendForm.submit();
                    }).catch(function(error) {
                        console.error('reCAPTCHA error:', error);
                        resendForm.submit();
                    });
                });
            } else {
                resendForm.submit();
            }
            @else
            resendButton.disabled = true;
            resendText.textContent = 'Sending...';
            @endif
            
            // Re-enable after 3 seconds
            setTimeout(() => {
                resendButton.disabled = false;
                resendText.textContent = 'Resend Code';
            }, 3000);
        });
    }
});
</script>
@endpush
@endsection
