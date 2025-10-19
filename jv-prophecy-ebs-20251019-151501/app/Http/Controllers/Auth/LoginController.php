<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SecurityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        // Log login attempt
        $this->logSecurityEvent('login_attempt', null, [
            'email' => $request->email,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            
            // Check if user is active
            if ($user->status !== 'active') {
                Auth::logout();
                $this->logSecurityEvent('login_failed', $user->id, [
                    'reason' => 'Account not active',
                    'status' => $user->status
                ], 'medium');
                
                throw ValidationException::withMessages([
                    'email' => ['Your account is not active. Please contact Voice of Jesus at vojmedia@gmail.com.'],
                ]);
            }

            // Check if email is verified
            if (!$user->hasVerifiedEmail()) {
                Auth::logout();
                $this->logSecurityEvent('login_failed', $user->id, [
                    'reason' => 'Email not verified'
                ], 'low');
                
                return redirect()->route('verification.show', ['email' => $user->email])
                    ->with('warning', 'Please verify your email address before logging in.');
            }

            // Check if user account is active (email verified)
            if (!$user->is_active) {
                Auth::logout();
                $this->logSecurityEvent('login_failed', $user->id, [
                    'reason' => 'Account not activated'
                ], 'low');
                
                return redirect()->route('verification.show', ['email' => $user->email])
                    ->with('warning', 'Please verify your email address to activate your account.');
            }

            $request->session()->regenerate();

            // Log successful login
            $this->logSecurityEvent('login_success', $user->id, [
                'email' => $user->email,
                'roles' => $user->roles->pluck('name')->toArray()
            ]);

            // Redirect based on user role
            if ($user->hasAnyRole(['super_admin', 'admin', 'editor'])) {
                return redirect()->intended('/admin/dashboard');
            }

            return redirect()->intended('/home');
        }

        // Log failed login
        $this->logSecurityEvent('login_failed', null, [
            'email' => $request->email,
            'reason' => 'Invalid credentials'
        ], 'medium');

        throw ValidationException::withMessages([
            'email' => ['The provided credentials do not match our records.'],
        ]);
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        
        if ($user) {
            $this->logSecurityEvent('logout', $user->id, [
                'email' => $user->email
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
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
