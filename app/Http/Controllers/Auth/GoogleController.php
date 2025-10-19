<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\SecurityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect to Google OAuth.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Check if user already exists
            $user = User::where('email', $googleUser->getEmail())->first();
            
            if ($user) {
                // Update existing user with Google info
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'email_verified_at' => now(),
                ]);
                
                // Log security event
                $this->logSecurityEvent('google_login_existing', $user->id, [
                    'email' => $user->email,
                    'google_id' => $googleUser->getId(),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]);
            } else {
                // Create new user
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => Hash::make(uniqid()), // Random password
                    'email_verified_at' => now(),
                    'status' => 'active',
                    'preferred_language' => 'en',
                ]);
                
                // Assign default user role
                $userRole = Role::where('name', 'user')->first();
                if ($userRole) {
                    $user->roles()->attach($userRole->id);
                }
                
                // Log security event
                $this->logSecurityEvent('google_registration', $user->id, [
                    'email' => $user->email,
                    'google_id' => $googleUser->getId(),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]);
            }
            
            // Login the user
            Auth::login($user, true);
            
            // Redirect based on user role
            if ($user->hasAnyRole(['super_admin', 'admin', 'editor'])) {
                return redirect()->route('admin.dashboard')->with('success', 'Successfully logged in with Google!');
            } else {
                return redirect()->route('home')->with('success', 'Welcome! You have successfully logged in with Google.');
            }
            
        } catch (\Exception $e) {
            // Log error
            $this->logSecurityEvent('google_login_error', null, [
                'error' => $e->getMessage(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ], 'high');
            
            return redirect()->route('login')->with('error', 'Unable to login with Google. Please try again or use email/password.');
        }
    }

    /**
     * Log security events.
     */
    private function logSecurityEvent($eventType, $userId = null, $metadata = [], $severity = 'low')
    {
        SecurityLog::create([
            'user_id' => $userId,
            'event_type' => $eventType,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'metadata' => json_encode($metadata),
            'severity' => $severity,
            'created_at' => now(),
        ]);
    }
}
