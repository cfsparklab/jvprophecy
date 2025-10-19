@extends('layouts.app')

@section('title', 'Login - Jebikalam Vaanga Prophecy')

@section('content')
<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: var(--space-lg);">
    <div style="width: 100%; max-width: 400px;">
        <!-- Login Card -->
        <div class="intel-card" style="padding: var(--space-2xl); border-radius: var(--radius-xl); margin-bottom: var(--space-lg);">
            <!-- Header -->
            <div style="text-align: center; margin-bottom: var(--space-2xl);">
                <div style="width: 80px; height: 80px; background: linear-gradient(135deg, var(--intel-blue-600), var(--intel-blue-700)); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg) auto; box-shadow: 0 8px 24px rgba(2, 132, 199, 0.3);">
                    <i class="fas fa-scroll" style="font-size: 2rem; color: white;"></i>
                </div>
                <h1 style="font-size: 1.875rem; font-weight: 700; color: var(--intel-gray-900); margin: 0 0 var(--space-sm) 0;">
                    Welcome Back!
                </h1>
                <p style="color: var(--intel-gray-600); margin: 0; font-size: 1rem;">
                    Sign in to access Prophecy
                </p>
            </div>

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf
                <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
                
                <!-- Email Field -->
                <div style="margin-bottom: var(--space-lg);">
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
                           placeholder="your.email@example.com"
                           onfocus="this.style.borderColor='var(--intel-blue-500)'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)';"
                           onblur="this.style.borderColor='var(--intel-gray-200)'; this.style.boxShadow='none';">
                    @error('email')
                    <div style="color: #ef4444; font-size: 0.875rem; margin-top: var(--space-xs);">
                        <i class="fas fa-exclamation-circle" style="margin-right: var(--space-xs);"></i>{{ $message }}
                    </div>
                    @enderror
                </div>
                
                <!-- Password Field -->
                <div style="margin-bottom: var(--space-lg);">
                    <label for="password" style="display: block; font-weight: 600; color: var(--intel-gray-700); margin-bottom: var(--space-sm); font-size: 0.875rem;">
                        <i class="fas fa-lock" style="margin-right: var(--space-sm); color: var(--intel-blue-600);"></i>Password
                    </label>
                    <input id="password" 
                           name="password" 
                           type="password" 
                           autocomplete="current-password" 
                           required
                           style="width: 100%; padding: var(--space-md); border: 2px solid var(--intel-gray-200); border-radius: var(--radius-md); font-size: 1rem; transition: all 0.2s ease; background: white; @error('password') border-color: #ef4444; @enderror"
                           placeholder="Enter your password"
                           onfocus="this.style.borderColor='var(--intel-blue-500)'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)';"
                           onblur="this.style.borderColor='var(--intel-gray-200)'; this.style.boxShadow='none';">
                    @error('password')
                    <div style="color: #ef4444; font-size: 0.875rem; margin-top: var(--space-xs);">
                        <i class="fas fa-exclamation-circle" style="margin-right: var(--space-xs);"></i>{{ $message }}
                    </div>
                    @enderror
                </div>
                
                <!-- Remember Me & Forgot Password -->
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: var(--space-xl);">
                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input type="checkbox" name="remember" style="margin-right: var(--space-sm); accent-color: var(--intel-blue-600);">
                        <span style="font-size: 0.875rem; color: var(--intel-gray-600);">Remember me</span>
                    </label>
                    <a href="{{ route('password.request') }}" style="font-size: 0.875rem; color: var(--intel-blue-600); text-decoration: none; font-weight: 500;">
                        Forgot password?
                    </a>
                </div>
                
                <!-- reCAPTCHA Error Display -->
                @error('recaptcha')
                <div style="color: #ef4444; font-size: 0.875rem; margin-bottom: var(--space-lg); padding: var(--space-sm); background: #fef2f2; border: 1px solid #fecaca; border-radius: var(--radius-md);">
                    <i class="fas fa-exclamation-triangle" style="margin-right: var(--space-xs);"></i>{{ $message }}
                </div>
                @enderror
                
                <!-- Submit Button -->
                <button type="submit" class="intel-btn-primary" style="width: 100%; padding: var(--space-md); border-radius: var(--radius-md); font-size: 1rem; font-weight: 600; border: none; cursor: pointer; transition: all 0.2s ease; margin-bottom: var(--space-xl);" id="loginBtn">
                    <i class="fas fa-sign-in-alt" style="margin-right: var(--space-sm);"></i>
                    Sign In Securely
                </button>
            </form>
            
            
            <!-- Register Link -->
            <div style="text-align: center;">
                <p style="font-size: 0.875rem; color: var(--intel-gray-600); margin: 0;">
                    Don't have an account?
                    <a href="{{ route('register') }}" style="color: var(--intel-blue-600); text-decoration: none; font-weight: 600; margin-left: var(--space-xs);">
                        Create one here
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
@if(app(\App\Services\RecaptchaService::class)->isEnabled())
<script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}" async defer></script>
@endif
<script>
// Enhanced form interactions
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');
    const submitButton = document.getElementById('loginBtn');
    
    if (!form || !submitButton) {
        console.error('Login form elements not found');
        return;
    }
    
    // Form submission handling with reCAPTCHA
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Reset button state
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right: var(--space-sm);"></i>Verifying...';
        submitButton.disabled = true;
        submitButton.style.opacity = '0.7';
        
        @if(app(\App\Services\RecaptchaService::class)->isEnabled())
        // Check if grecaptcha is available
        if (typeof grecaptcha === 'undefined') {
            console.error('reCAPTCHA not loaded');
            showError('Security verification service is not available. Please refresh the page and try again.');
            resetButton();
            return;
        }
        
        // Execute reCAPTCHA
        try {
            grecaptcha.ready(function() {
                grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {action: 'login'})
                    .then(function(token) {
                        if (token) {
                            document.getElementById('g-recaptcha-response').value = token;
                            
                            // Update button text and submit
                            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right: var(--space-sm);"></i>Signing In...';
                            
                            // Submit form normally
                            form.submit();
                        } else {
                            showError('Security verification failed. Please try again.');
                            resetButton();
                        }
                    })
                    .catch(function(error) {
                        console.error('reCAPTCHA execution error:', error);
                        showError('Security verification failed. Please try again.');
                        resetButton();
                    });
            });
        } catch (error) {
            console.error('reCAPTCHA error:', error);
            showError('Security verification service is temporarily unavailable. Please try again later.');
            resetButton();
        }
        @else
        // If reCAPTCHA is disabled, submit directly
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right: var(--space-sm);"></i>Signing In...';
        form.submit();
        @endif
    });
    
    // Helper function to show errors
    function showError(message) {
        // Remove existing error messages
        const existingError = document.querySelector('.recaptcha-error');
        if (existingError) {
            existingError.remove();
        }
        
        // Create error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'recaptcha-error';
        errorDiv.style.cssText = 'color: #ef4444; font-size: 0.875rem; margin-bottom: var(--space-lg); padding: var(--space-sm); background: #fef2f2; border: 1px solid #fecaca; border-radius: var(--radius-md);';
        errorDiv.innerHTML = '<i class="fas fa-exclamation-triangle" style="margin-right: var(--space-xs);"></i>' + message;
        
        // Insert before submit button
        submitButton.parentNode.insertBefore(errorDiv, submitButton);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (errorDiv.parentNode) {
                errorDiv.remove();
            }
        }, 5000);
    }
    
    // Helper function to reset button
    function resetButton() {
        submitButton.innerHTML = '<i class="fas fa-sign-in-alt" style="margin-right: var(--space-sm);"></i>Sign In Securely';
        submitButton.disabled = false;
        submitButton.style.opacity = '1';
    }
    
    // Input focus effects
    const inputs = document.querySelectorAll('input[type="email"], input[type="password"]');
    inputs.forEach(input => {
        if (input) {
        input.addEventListener('focus', function() {
            this.style.transform = 'scale(1.01)';
        });
        
        input.addEventListener('blur', function() {
            this.style.transform = 'scale(1)';
        });
        }
    });
    
    // Log login attempt
    if (typeof logActivity === 'function') {
        try {
        logActivity('login_page_view', {
            timestamp: new Date().toISOString(),
            user_agent: navigator.userAgent
        });
        } catch (error) {
            console.warn('Activity logging failed:', error);
        }
    }
});
</script>
@endpush
@endsection