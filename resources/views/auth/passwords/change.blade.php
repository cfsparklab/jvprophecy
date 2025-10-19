@extends('layouts.app')

@section('title', 'Change Password - JV Prophecy Manager')

@section('content')
<div style="min-height: 100vh; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; padding: 2rem;">
    <div style="width: 100%; max-width: 500px;">
        
        <!-- Logo & Header -->
        <div style="text-align: center; margin-bottom: 2rem;">
            <div style="background: white; display: inline-flex; align-items: center; justify-content: center; width: 80px; height: 80px; border-radius: 20px; margin-bottom: 1rem; box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);">
                <i class="fas fa-key" style="font-size: 2.5rem; color: #667eea;"></i>
            </div>
            <h1 style="color: white; font-size: 2rem; font-weight: 700; margin: 0 0 0.5rem 0;">Change Password</h1>
            <p style="color: rgba(255, 255, 255, 0.9); font-size: 1rem; margin: 0;">
                Update your account password securely
            </p>
        </div>

        <!-- Change Password Card -->
        <div style="background: white; border-radius: 16px; padding: 2.5rem; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);">
            
            <!-- User Info -->
            <div style="background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%); border-radius: 12px; padding: 1rem; margin-bottom: 2rem; border-left: 4px solid #667eea;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <i class="fas fa-user-circle" style="font-size: 2rem; color: #667eea;"></i>
                    <div>
                        <div style="font-weight: 600; color: #1f2937;">{{ Auth::user()->name }}</div>
                        <div style="font-size: 0.875rem; color: #6b7280;">{{ Auth::user()->email }}</div>
                    </div>
                </div>
            </div>

            <!-- Success Message -->
            @if(session('success'))
            <div style="background: #d1fae5; border: 1px solid #6ee7b7; color: #065f46; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
                <i class="fas fa-check-circle" style="font-size: 1.25rem;"></i>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            <!-- Error Message -->
            @if(session('error'))
            <div style="background: #fee2e2; border: 1px solid #fecaca; color: #991b1b; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
                <i class="fas fa-exclamation-circle" style="font-size: 1.25rem;"></i>
                <span>{{ session('error') }}</span>
            </div>
            @endif

            <!-- Change Password Form -->
            <form method="POST" action="{{ route('change-password.update') }}">
                @csrf
                
                <!-- Current Password -->
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem; font-size: 0.875rem;">
                        <i class="fas fa-lock" style="color: #667eea; margin-right: 0.5rem;"></i>
                        Current Password
                    </label>
                    <input type="password" 
                           name="current_password" 
                           id="current_password"
                           required
                           style="width: 100%; padding: 0.75rem 1rem; border: 2px solid {{ $errors->has('current_password') ? '#ef4444' : '#e5e7eb' }}; border-radius: 8px; font-size: 1rem; transition: all 0.2s; outline: none;"
                           onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)';"
                           onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                    @error('current_password')
                    <p style="color: #ef4444; font-size: 0.875rem; margin-top: 0.5rem; display: flex; align-items: center; gap: 0.25rem;">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- New Password -->
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem; font-size: 0.875rem;">
                        <i class="fas fa-key" style="color: #667eea; margin-right: 0.5rem;"></i>
                        New Password
                    </label>
                    <input type="password" 
                           name="new_password" 
                           id="new_password"
                           required
                           style="width: 100%; padding: 0.75rem 1rem; border: 2px solid {{ $errors->has('new_password') ? '#ef4444' : '#e5e7eb' }}; border-radius: 8px; font-size: 1rem; transition: all 0.2s; outline: none;"
                           onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)';"
                           onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                    @error('new_password')
                    <p style="color: #ef4444; font-size: 0.875rem; margin-top: 0.5rem; display: flex; align-items: center; gap: 0.25rem;">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </p>
                    @enderror
                    <p style="color: #6b7280; font-size: 0.8rem; margin-top: 0.5rem;">
                        <i class="fas fa-info-circle"></i> Minimum 8 characters, must be different from current password
                    </p>
                </div>

                <!-- Confirm New Password -->
                <div style="margin-bottom: 2rem;">
                    <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem; font-size: 0.875rem;">
                        <i class="fas fa-check-double" style="color: #667eea; margin-right: 0.5rem;"></i>
                        Confirm New Password
                    </label>
                    <input type="password" 
                           name="new_password_confirmation" 
                           id="new_password_confirmation"
                           required
                           style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem; transition: all 0.2s; outline: none;"
                           onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)';"
                           onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        style="width: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 1rem; border-radius: 8px; font-weight: 600; font-size: 1rem; border: none; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);"
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(102, 126, 234, 0.5)';"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(102, 126, 234, 0.4)';">
                    <i class="fas fa-save"></i> Change Password
                </button>
            </form>

            <!-- Additional Info -->
            <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #e5e7eb;">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                    <a href="{{ route('home') }}" 
                       style="color: #667eea; text-decoration: none; font-weight: 500; display: flex; align-items: center; gap: 0.5rem; transition: all 0.2s;"
                       onmouseover="this.style.color='#764ba2';"
                       onmouseout="this.style.color='#667eea';">
                        <i class="fas fa-arrow-left"></i> Back to Home
                    </a>
                    
                    @if(Auth::user()->hasRole(['super_admin', 'admin', 'editor']))
                    <a href="{{ route('admin.dashboard') }}" 
                       style="color: #667eea; text-decoration: none; font-weight: 500; display: flex; align-items: center; gap: 0.5rem; transition: all 0.2s;"
                       onmouseover="this.style.color='#764ba2';"
                       onmouseout="this.style.color='#667eea';">
                        <i class="fas fa-tachometer-alt"></i> Admin Dashboard
                    </a>
                    @endif
                </div>
            </div>

            <!-- Security Tips -->
            <div style="margin-top: 1.5rem; background: #f9fafb; border-radius: 8px; padding: 1rem;">
                <div style="font-weight: 600; color: #374151; margin-bottom: 0.75rem; font-size: 0.875rem;">
                    <i class="fas fa-shield-alt" style="color: #667eea;"></i> Password Security Tips
                </div>
                <ul style="margin: 0; padding-left: 1.5rem; color: #6b7280; font-size: 0.8rem; line-height: 1.6;">
                    <li>Use at least 8 characters</li>
                    <li>Include uppercase and lowercase letters</li>
                    <li>Add numbers and special characters</li>
                    <li>Don't use common words or personal info</li>
                    <li>Change your password regularly</li>
                </ul>
            </div>
        </div>

        <!-- Footer -->
        <div style="text-align: center; margin-top: 2rem; color: rgba(255, 255, 255, 0.8); font-size: 0.875rem;">
            <p style="margin: 0;">
                Secured • {{ now()->format('d/m/Y H:i:s') }} IST • IP: {{ request()->ip() }}
            </p>
        </div>
    </div>
</div>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<script>
// Password strength indicator
document.getElementById('new_password').addEventListener('input', function(e) {
    const password = e.target.value;
    let strength = 0;
    
    if (password.length >= 8) strength++;
    if (password.match(/[a-z]/)) strength++;
    if (password.match(/[A-Z]/)) strength++;
    if (password.match(/[0-9]/)) strength++;
    if (password.match(/[^a-zA-Z0-9]/)) strength++;
    
    const colors = ['#ef4444', '#f59e0b', '#eab308', '#84cc16', '#22c55e'];
    const labels = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong'];
    
    if (password.length > 0) {
        // You can add a strength indicator here if desired
        console.log('Password strength:', labels[strength - 1], colors[strength - 1]);
    }
});

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('new_password_confirmation').value;
    
    if (newPassword !== confirmPassword) {
        e.preventDefault();
        alert('New password and confirmation do not match!');
        return false;
    }
    
    if (newPassword.length < 8) {
        e.preventDefault();
        alert('Password must be at least 8 characters long!');
        return false;
    }
});
</script>
@endsection

