@extends('layouts.app')

@section('title', 'Register - Jebikalam Vaanga Prophecy')

@section('content')
<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: var(--space-lg);">
    <div style="width: 100%; max-width: 500px;">
        <!-- Registration Card -->
        <div class="intel-card" style="padding: var(--space-2xl); border-radius: var(--radius-xl); margin-bottom: var(--space-lg);">
            <!-- Header -->
            <div style="text-align: center; margin-bottom: var(--space-2xl);">
                <div style="width: 80px; height: 80px; background: linear-gradient(135deg, var(--intel-blue-600), var(--intel-blue-700)); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg) auto; box-shadow: 0 8px 24px rgba(2, 132, 199, 0.3);">
                    <i class="fas fa-praying-hands" style="font-size: 2rem; color: white;"></i>
                </div>
                <h1 style="font-size: 1.875rem; font-weight: 700; color: var(--intel-gray-900); margin: 0 0 var(--space-sm) 0;">
                    Jebikalam Vaanga Prophecy
                </h1>
                <p style="color: var(--intel-gray-600); margin: 0; font-size: 1rem;">
                    Create your account to access Prophecy
                </p>
            </div>

            <!-- Registration Form -->
            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf
                <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
                
                <!-- Full Name Field -->
                <div style="margin-bottom: var(--space-lg);">
                    <label for="name" style="display: block; font-weight: 600; color: var(--intel-gray-700); margin-bottom: var(--space-sm); font-size: 0.875rem;">
                        <i class="fas fa-user" style="margin-right: var(--space-sm); color: var(--intel-blue-600);"></i>Full Name
                    </label>
                    <input id="name" 
                           name="name" 
                           type="text" 
                           autocomplete="name" 
                           required
                           value="{{ old('name') }}"
                           style="width: 100%; padding: var(--space-md); border: 2px solid var(--intel-gray-200); border-radius: var(--radius-md); font-size: 1rem; transition: all 0.2s ease; background: white; @error('name') border-color: #ef4444; @enderror"
                           placeholder="Enter your full name"
                           onfocus="this.style.borderColor='var(--intel-blue-500)'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)';"
                           onblur="this.style.borderColor='var(--intel-gray-200)'; this.style.boxShadow='none';">
                    @error('name')
                    <div style="color: #ef4444; font-size: 0.875rem; margin-top: var(--space-xs);">
                        <i class="fas fa-exclamation-circle" style="margin-right: var(--space-xs);"></i>{{ $message }}
                    </div>
                    @enderror
                </div>
                
                <!-- Email Field -->
                <div style="margin-bottom: var(--space-lg);">
                    <label for="email" style="display: block; font-weight: 600; color: var(--intel-gray-700); margin-bottom: var(--space-sm); font-size: 0.875rem;">
                        <i class="fas fa-envelope" style="margin-right: var(--space-sm); color: var(--intel-blue-600);"></i>Email address
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
                
                <!-- Mobile Field -->
                <div style="margin-bottom: var(--space-lg);">
                    <label for="mobile" style="display: block; font-weight: 600; color: var(--intel-gray-700); margin-bottom: var(--space-sm); font-size: 0.875rem;">
                        <i class="fas fa-phone" style="margin-right: var(--space-sm); color: var(--intel-blue-600);"></i>Mobile Number
                    </label>
                    <input id="mobile" 
                           name="mobile" 
                           type="tel" 
                           autocomplete="tel" 
                           required
                           value="{{ old('mobile') }}"
                           style="width: 100%; padding: var(--space-md); border: 2px solid var(--intel-gray-200); border-radius: var(--radius-md); font-size: 1rem; transition: all 0.2s ease; background: white; @error('mobile') border-color: #ef4444; @enderror"
                           placeholder="Enter your mobile number"
                           onfocus="this.style.borderColor='var(--intel-blue-500)'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)';"
                           onblur="this.style.borderColor='var(--intel-gray-200)'; this.style.boxShadow='none';">
                    @error('mobile')
                    <div style="color: #ef4444; font-size: 0.875rem; margin-top: var(--space-xs);">
                        <i class="fas fa-exclamation-circle" style="margin-right: var(--space-xs);"></i>{{ $message }}
                    </div>
                    @enderror
                </div>
                
                <!-- Preferred Language Field -->
                <div style="margin-bottom: var(--space-lg);">
                    <label for="preferred_language" style="display: block; font-weight: 600; color: var(--intel-gray-700); margin-bottom: var(--space-sm); font-size: 0.875rem;">
                        <i class="fas fa-globe" style="margin-right: var(--space-sm); color: var(--intel-blue-600);"></i>Preferred Language
                    </label>
                    <select id="preferred_language" 
                            name="preferred_language" 
                            required
                            style="width: 100%; padding: var(--space-md); border: 2px solid var(--intel-gray-200); border-radius: var(--radius-md); font-size: 1rem; transition: all 0.2s ease; background: white; @error('preferred_language') border-color: #ef4444; @enderror"
                            onfocus="this.style.borderColor='var(--intel-blue-500)'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)';"
                            onblur="this.style.borderColor='var(--intel-gray-200)'; this.style.boxShadow='none';">
                        <option value="">Select your preferred language</option>
                        <option value="en" {{ old('preferred_language') === 'en' ? 'selected' : '' }}>English</option>
                        <option value="ta" {{ old('preferred_language') === 'ta' ? 'selected' : '' }}>தமிழ் (Tamil)</option>
                        <option value="kn" {{ old('preferred_language') === 'kn' ? 'selected' : '' }}>ಕನ್ನಡ (Kannada)</option>
                        <option value="te" {{ old('preferred_language') === 'te' ? 'selected' : '' }}>తెలుగు (Telugu)</option>
                        <option value="ml" {{ old('preferred_language') === 'ml' ? 'selected' : '' }}>മലയാളം (Malayalam)</option>
                        <option value="hi" {{ old('preferred_language') === 'hi' ? 'selected' : '' }}>हिंदी (Hindi)</option>
                    </select>
                    @error('preferred_language')
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
                           autocomplete="new-password" 
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
                
                <!-- Confirm Password Field -->
                <div style="margin-bottom: var(--space-xl);">
                    <label for="password_confirmation" style="display: block; font-weight: 600; color: var(--intel-gray-700); margin-bottom: var(--space-sm); font-size: 0.875rem;">
                        <i class="fas fa-lock" style="margin-right: var(--space-sm); color: var(--intel-blue-600);"></i>Confirm Password
                    </label>
                    <input id="password_confirmation" 
                           name="password_confirmation" 
                           type="password" 
                           autocomplete="new-password" 
                           required
                           style="width: 100%; padding: var(--space-md); border: 2px solid var(--intel-gray-200); border-radius: var(--radius-md); font-size: 1rem; transition: all 0.2s ease; background: white;"
                           placeholder="Confirm your password"
                           onfocus="this.style.borderColor='var(--intel-blue-500)'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)';"
                           onblur="this.style.borderColor='var(--intel-gray-200)'; this.style.boxShadow='none';">
                </div>
                
                <!-- reCAPTCHA Error Display -->
                @error('recaptcha')
                <div style="color: #ef4444; font-size: 0.875rem; margin-bottom: var(--space-lg); padding: var(--space-sm); background: #fef2f2; border: 1px solid #fecaca; border-radius: var(--radius-md);">
                    <i class="fas fa-exclamation-triangle" style="margin-right: var(--space-xs);"></i>{{ $message }}
                </div>
                @enderror

                <!-- Submit Button -->
                <button type="submit" class="intel-btn-primary" style="width: 100%; padding: var(--space-md); border-radius: var(--radius-md); font-size: 1rem; font-weight: 600; border: none; cursor: pointer; transition: all 0.2s ease; margin-bottom: var(--space-xl);" id="registerBtn">
                    <i class="fas fa-user-plus" style="margin-right: var(--space-sm);"></i>
                    Create Account
                </button>
            </form>
            
            <!-- Login Link -->
            <div style="text-align: center;">
                <p style="font-size: 0.875rem; color: var(--intel-gray-600); margin: 0;">
                    Already have an account?
                    <a href="{{ route('login') }}" style="color: var(--intel-blue-600); text-decoration: none; font-weight: 600; margin-left: var(--space-xs);">
                        Sign in here
                    </a>
                </p>
            </div>
        </div>
        
        <!-- Terms Notice -->
        <div style="text-align: center; font-size: 0.75rem; color: var(--intel-gray-500); margin-top: var(--space-lg);">
            <p style="margin: 0 0 var(--space-xs) 0;">By creating an account, you agree to our Terms of Service and Privacy Policy.</p>
            <p style="margin: 0;">Email verification will be required after registration.</p>
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
    const form = document.getElementById('registerForm');
    const submitButton = document.getElementById('registerBtn');
    
    if (!form || !submitButton) {
        console.error('Register form elements not found');
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
                grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {action: 'register'})
                    .then(function(token) {
                        if (token) {
                            document.getElementById('g-recaptcha-response').value = token;
                            
                            // Update button text and submit
                            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right: var(--space-sm);"></i>Creating Account...';
                            
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
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right: var(--space-sm);"></i>Creating Account...';
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
        submitButton.innerHTML = '<i class="fas fa-user-plus" style="margin-right: var(--space-sm);"></i>Create Account';
        submitButton.disabled = false;
        submitButton.style.opacity = '1';
    }
    
    // Input focus effects
    const inputs = document.querySelectorAll('input, select');
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
    
    // Password confirmation validation
    const password = document.getElementById('password');
    const passwordConfirmation = document.getElementById('password_confirmation');
    
    function validatePasswordMatch() {
        if (passwordConfirmation && passwordConfirmation.value && password && password.value !== passwordConfirmation.value) {
            passwordConfirmation.style.borderColor = '#ef4444';
        } else if (passwordConfirmation) {
            passwordConfirmation.style.borderColor = 'var(--intel-gray-200)';
        }
    }
    
    if (password && passwordConfirmation) {
        password.addEventListener('input', validatePasswordMatch);
        passwordConfirmation.addEventListener('input', validatePasswordMatch);
    }
    
    // Log registration attempt
    if (typeof logActivity === 'function') {
        try {
            logActivity('register_page_view', {
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