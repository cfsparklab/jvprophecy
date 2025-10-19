@extends('layouts.app')

@section('title', 'Reset Password - Jebikalam Vaanga Prophecy')

@section('content')
<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: var(--space-lg);">
    <div style="width: 100%; max-width: 480px;">
        <!-- Forgot Password Card -->
        <div class="intel-card" style="padding: var(--space-2xl); border-radius: var(--radius-xl); margin-bottom: var(--space-lg);">
            
            <!-- Header -->
            <div style="text-align: center; margin-bottom: var(--space-2xl);">
                <div style="width: 80px; height: 80px; background: linear-gradient(135deg, var(--intel-blue-600), var(--intel-blue-700)); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg) auto; box-shadow: 0 8px 24px rgba(2, 132, 199, 0.3);">
                    <i class="fas fa-key" style="font-size: 2rem; color: white;"></i>
                </div>
                <h1 style="font-size: 1.875rem; font-weight: 700; color: var(--intel-gray-900); margin: 0 0 var(--space-sm) 0;">
                    Reset Password
                </h1>
                <p style="color: var(--intel-gray-600); margin: 0; font-size: 1rem;">
                    Enter your email address and we'll send you a password reset link
                </p>
            </div>

            <!-- Success Message -->
            @if (session('status'))
                <div style="padding: 1rem 1.25rem; border-radius: 12px; margin-bottom: 1.5rem; background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534;">
                    <i class="fas fa-check-circle"></i> {{ session('status') }}
                </div>
            @endif

            <!-- Error Messages -->
            @if ($errors->any())
                <div style="padding: 1rem 1.25rem; border-radius: 12px; margin-bottom: 1.5rem; background: #fef2f2; border: 1px solid #fecaca; color: #dc2626;">
                    <i class="fas fa-exclamation-circle"></i>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <!-- reCAPTCHA Error Display -->
            @error('recaptcha')
            <div style="color: #ef4444; font-size: 0.875rem; margin-bottom: var(--space-lg); padding: var(--space-sm); background: #fef2f2; border: 1px solid #fecaca; border-radius: var(--radius-md);">
                <i class="fas fa-exclamation-triangle" style="margin-right: var(--space-xs);"></i>{{ $message }}
            </div>
            @enderror

            <!-- Reset Password Form -->
            <form method="POST" action="{{ route('password.email') }}" id="forgotPasswordForm">
                @csrf
                <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">

                <!-- Email Field -->
                <div style="margin-bottom: var(--space-xl);">
                    <label for="email" style="display: block; font-weight: 600; color: var(--intel-gray-700); margin-bottom: var(--space-sm); font-size: 0.875rem;">
                        <i class="fas fa-envelope" style="margin-right: var(--space-sm); color: var(--intel-blue-600);"></i>Email Address
                    </label>
                    <input id="email" 
                           name="email" 
                           type="email" 
                           autocomplete="email" 
                           required
                           value="{{ old('email') }}"
                           style="width: 100%; padding: var(--space-md); border: 2px solid var(--intel-gray-200); border-radius: var(--radius-md); font-size: 1rem; transition: all 0.2s ease; background: white; @error('email') border-color: #ef4444; @enderror"
                           placeholder="Enter your email address"
                           onfocus="this.style.borderColor='var(--intel-blue-500)'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)';"
                           onblur="this.style.borderColor='var(--intel-gray-200)'; this.style.boxShadow='none';">
                    @error('email')
                    <div style="color: #ef4444; font-size: 0.875rem; margin-top: var(--space-xs);">
                        <i class="fas fa-exclamation-circle" style="margin-right: var(--space-xs);"></i>{{ $message }}
                    </div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" id="resetButton" style="width: 100%; height: 56px; background: linear-gradient(135deg, var(--intel-blue-600), var(--intel-blue-700)); border: none; border-radius: var(--radius-md); color: white; font-size: 1.1rem; font-weight: 600; cursor: pointer; margin: 1.5rem 0;">
                    <i class="fas fa-paper-plane"></i>
                    <span>Send Reset Link</span>
                </button>
            </form>

            <!-- Back to Login -->
            <div style="text-align: center; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #f1f5f9;">
                <a href="{{ route('login') }}" style="color: var(--intel-blue-600); text-decoration: none; font-size: 0.9rem; font-weight: 500;">
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
    const form = document.getElementById('forgotPasswordForm');
    const resetButton = document.getElementById('resetButton');

    if (form && resetButton) {
        form.addEventListener('submit', function(e) {
            const email = document.getElementById('email').value.trim();
            
            // Basic validation
            if (!email || !email.includes('@')) {
                e.preventDefault();
                alert('Please enter a valid email address.');
                document.getElementById('email').focus();
                return false;
            }

            // Handle reCAPTCHA if enabled
            @if(app(\App\Services\RecaptchaService::class)->isEnabled())
            e.preventDefault();
            
            resetButton.disabled = true;
            resetButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Sending...</span>';
            
            if (typeof grecaptcha !== 'undefined') {
                grecaptcha.ready(function() {
                    grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {action: 'forgot_password'}).then(function(token) {
                        document.getElementById('g-recaptcha-response').value = token;
                        form.submit();
                    }).catch(function(error) {
                        console.error('reCAPTCHA error:', error);
                        form.submit();
                    });
                });
            } else {
                form.submit();
            }
            @else
            resetButton.disabled = true;
            resetButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Sending...</span>';
            @endif
        });
    }
});
</script>
@endpush
@endsection
