<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    /**
     * Show the password reset form.
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset')->with([
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * Reset the given user's password.
     */
    public function reset(Request $request)
    {
        Log::info('Password reset attempt', [
            'email' => $request->input('email'),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
        ], [
            'token.required' => 'Reset token is required.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.exists' => 'We could not find a user with that email address.',
            'password.required' => 'Please enter a new password.',
            'password.min' => 'Password must be at least 8 characters long.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        if ($validator->fails()) {
            Log::warning('Password reset validation failed', [
                'email' => $request->input('email'),
                'errors' => $validator->errors()->toArray()
            ]);
            
            return back()
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }

        // Check if user is active
        $user = User::where('email', $request->email)->first();
        if (!$user || !$user->is_active) {
            Log::warning('Password reset attempted for inactive user', [
                'email' => $request->input('email'),
                'user_active' => $user ? $user->is_active : false
            ]);
            
            return back()
                ->withErrors(['email' => 'This account is not active. Please contact support.'])
                ->withInput($request->only('email'));
        }

        // Reset the password
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));

                Log::info('Password reset successful', [
                    'user_id' => $user->id,
                    'email' => $user->email
                ]);
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            Log::info('Password reset completed successfully', [
                'email' => $request->input('email')
            ]);
            
            // Auto-login the user after successful password reset
            $user = User::where('email', $request->email)->first();
            if ($user) {
                Auth::login($user);
                
                return redirect()->route('user.dashboard')->with('success', 'Your password has been reset successfully! You are now logged in.');
            }
            
            return redirect()->route('login')->with('success', 'Your password has been reset successfully! Please log in with your new password.');
        } else {
            Log::error('Password reset failed', [
                'email' => $request->input('email'),
                'status' => $status
            ]);
            
            return back()
                ->withErrors(['email' => 'We encountered an error resetting your password. The reset link may have expired.'])
                ->withInput($request->only('email'));
        }
    }
}
