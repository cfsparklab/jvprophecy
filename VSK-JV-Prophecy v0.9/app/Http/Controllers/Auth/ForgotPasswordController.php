<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    /**
     * Show the form for requesting a password reset link.
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * Send a reset link to the given user.
     */
    public function sendResetLinkEmail(Request $request)
    {
        Log::info('Password reset request received', [
            'email' => $request->input('email'),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.exists' => 'We could not find a user with that email address.',
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

        // Send the password reset link
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            Log::info('Password reset link sent successfully', [
                'email' => $request->input('email')
            ]);
            
            return back()->with('status', 'We have sent you a password reset link! Please check your email.');
        } else {
            Log::error('Failed to send password reset link', [
                'email' => $request->input('email'),
                'status' => $status
            ]);
            
            return back()
                ->withErrors(['email' => 'We encountered an error sending the reset link. Please try again.'])
                ->withInput($request->only('email'));
        }
    }
}
