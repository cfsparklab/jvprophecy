<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\SecurityLog;
use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration request.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'mobile' => 'required|string|max:15|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'preferred_language' => 'required|string|in:en,ta,kn,te,ml,hi',
        ]);

        // Log registration attempt
        $this->logSecurityEvent('registration_attempt', null, [
            'email' => $request->email,
            'mobile' => $request->mobile,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
            'preferred_language' => $request->preferred_language,
            'status' => 'active',
            'is_active' => false, // User inactive until email verified
        ]);

        // Generate email verification token and code
        $user->generateEmailVerification();

        // Assign default user role
        $userRole = Role::where('name', 'user')->first();
        if ($userRole) {
            $user->roles()->attach($userRole->id, [
                'assigned_at' => now(),
                'assigned_by' => $user->id
            ]);
        }

        // Send email verification notification
        $user->notify(new EmailVerificationNotification($user));

        // Log successful registration
        $this->logSecurityEvent('registration_success', $user->id, [
            'email' => $user->email,
            'mobile' => $user->mobile,
            'preferred_language' => $user->preferred_language,
            'verification_sent' => true
        ]);

        // Note: Not firing Registered event to avoid Laravel's default email verification
        // We're manually sending our custom notification above

        // Don't auto-login, redirect to verification page
        return redirect()->route('verification.show', ['email' => $user->email])
            ->with('success', 'Registration successful! Please check your email for verification code. You will be automatically logged in after verification.');
    }

    /**
     * Log security events.
     */
    private function logSecurityEvent($eventType, $userId = null, $metadata = [], $severity = 'low')
    {
        SecurityLog::create([
            'user_id' => $userId,
            'event_type' => $eventType,
            'resource_type' => 'auth',
            'resource_id' => null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'metadata' => $metadata,
            'severity' => $severity,
            'event_time' => now(),
        ]);
    }
}
