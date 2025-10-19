@extends('layouts.app')

@section('title', 'Verify Your Email - Jebikalam Vaanga Prophecy')

@section('content')
<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: var(--space-lg);">
    <div style="width: 100%; max-width: 500px;">
        <!-- Card Container -->
        <div class="card-premium" style="padding: var(--space-xl); text-align: center;">
            <!-- Logo -->
            <div style="margin-bottom: var(--space-xl);">
                <div style="display: flex; align-items: center; justify-content: center; gap: var(--space-md); margin-bottom: var(--space-lg);">
                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, var(--intel-blue-500), var(--intel-blue-600)); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-envelope" style="color: white; font-size: 1.5rem;"></i>
                    </div>
                    <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--intel-gray-900); margin: 0;">Email Verification</h1>
                </div>
            </div>

            <!-- Verification Message -->
            <div style="margin-bottom: var(--space-xl);">
                <h2 style="font-size: 1.25rem; font-weight: 600; color: var(--intel-gray-900); margin-bottom: var(--space-md);">
                    Check Your Email
                </h2>
                <p style="color: var(--intel-gray-600); line-height: 1.6; margin-bottom: var(--space-md);">
                    We've sent a verification email to your registered email address. Please check your inbox and click the verification link or enter the 6-digit code to activate your account.
                </p>
                <div style="background: #f0f9ff; border: 1px solid #0ea5e9; border-radius: 8px; padding: var(--space-md); margin-bottom: var(--space-lg);">
                    <p style="margin: 0; color: #0369a1; font-size: 0.875rem; font-weight: 500;">
                        <i class="fas fa-info-circle" style="margin-right: var(--space-sm);"></i>
                        You'll be automatically logged in after verification
                    </p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div style="display: flex; flex-direction: column; gap: var(--space-md);">
                <a href="{{ route('verification.show', ['email' => request()->user()?->email ?? '']) }}" 
                   class="btn-primary" 
                   style="text-decoration: none; display: inline-block; text-align: center;">
                    <i class="fas fa-keyboard" style="margin-right: var(--space-sm);"></i>
                    Enter Verification Code
                </a>
                
                <form method="POST" action="{{ route('verification.resend') }}" style="margin: 0;" id="resendEmailForm">
                    @csrf
                    <input type="hidden" name="email" value="{{ request()->user()?->email ?? '' }}">
                    <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response-email">
                    <button type="submit" class="btn-secondary" style="width: 100%;" id="resendEmailBtn">
                        <i class="fas fa-paper-plane" style="margin-right: var(--space-sm);"></i>
                        <span id="resendEmailText">Resend Verification Email</span>
                    </button>
                </form>
            </div>

            <!-- Help Text -->
            <div style="margin-top: var(--space-xl); padding-top: var(--space-lg); border-top: 1px solid var(--intel-gray-200);">
                <p style="font-size: 0.875rem; color: var(--intel-gray-500); margin-bottom: var(--space-md);">
                    Your verification link and code remain valid until you verify your account. Check your spam folder if you don't see the email.
                </p>
                <a href="{{ route('login') }}" style="color: var(--intel-blue-600); text-decoration: none; font-size: 0.875rem;">
                    ← Back to Login
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
    const resendForm = document.getElementById('resendEmailForm');
    const resendBtn = document.getElementById('resendEmailBtn');
    const resendText = document.getElementById('resendEmailText');
    
    if (resendForm && resendBtn && resendText) {
        resendForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            resendBtn.disabled = true;
            resendText.textContent = 'Sending...';
            
            @if(app(\App\Services\RecaptchaService::class)->isEnabled())
            if (typeof grecaptcha !== 'undefined') {
                grecaptcha.ready(function() {
                    grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {action: 'resend_email'}).then(function(token) {
                        document.getElementById('g-recaptcha-response-email').value = token;
                        resendForm.submit();
                    }).catch(function(error) {
                        console.error('reCAPTCHA error:', error);
                        resendForm.submit(); // Submit anyway
                    });
                });
            } else {
                resendForm.submit();
            }
            @else
            resendForm.submit();
            @endif
            
            // Re-enable after 3 seconds to prevent spam
            setTimeout(() => {
                resendBtn.disabled = false;
                resendText.textContent = 'Resend Verification Email';
            }, 3000);
        });
    }
});
</script>
@endpush
@endsection
