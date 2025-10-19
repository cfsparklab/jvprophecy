<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class EmailVerificationController extends Controller
{
    /**
     * Show the email verification notice.
     */
    public function notice()
    {
        if (Auth::check() && Auth::user()->hasVerifiedEmail()) {
            return redirect()->route('home');
        }

        return view('auth.verify-email');
    }

    /**
     * Show the email verification form (for entering code).
     */
    public function show(Request $request)
    {
        $email = $request->get('email');
        
        if (!$email) {
            return redirect()->route('login')->with('error', 'Invalid verification request.');
        }

        return view('auth.verify-code', compact('email'));
    }

    /**
     * Handle email verification via link.
     */
    public function verify(Request $request)
    {
        Log::info('Email verification link accessed', [
            'id' => $request->route('id'),
            'hash' => $request->route('hash'),
            'url' => $request->fullUrl(),
            'ip' => $request->ip()
        ]);

        $user = User::find($request->route('id'));

        if (!$user) {
            Log::warning('Verification failed: User not found', ['id' => $request->route('id')]);
            return redirect()->route('login')->with('error', 'Invalid verification link.');
        }

        Log::info('User found for verification', [
            'user_id' => $user->id,
            'email' => $user->email,
            'is_verified' => $user->hasVerifiedEmail(),
            'is_active' => $user->is_active
        ]);

        // Check if the hash matches
        $expectedHash = sha1($user->email);
        $providedHash = (string) $request->route('hash');
        
        Log::info('Hash verification', [
            'expected_hash' => $expectedHash,
            'provided_hash' => $providedHash,
            'matches' => hash_equals($providedHash, $expectedHash)
        ]);
        
        if (!hash_equals($providedHash, $expectedHash)) {
            Log::warning('Verification failed: Hash mismatch');
            return redirect()->route('login')->with('error', 'Invalid verification link.');
        }

        // Token validation is handled via the signed URL and hash verification

        // Check if URL signature is valid (permanent links don't expire)
        Log::info('Checking URL signature validity');
        if (!URL::hasValidSignature($request)) {
            Log::warning('Verification failed: Invalid URL signature');
            return redirect()->route('login')->with('error', 'Invalid verification link.');
        }
        
        Log::info('URL signature is valid');

        if ($user->hasVerifiedEmail()) {
            // Auto-login if already verified
            Auth::login($user);
            return redirect()->route('home')->with('success', 'Welcome back! Your email is already verified.');
        }

        $user->markEmailAsVerified();

        Log::info('User email verified via link', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        // Auto-login after successful verification
        Auth::login($user);

        return redirect()->route('home')->with('success', 'Email verified successfully! Welcome to Jebikalam Vaanga Prophecy!');
    }

    /**
     * Handle email verification via code.
     */
    public function verifyCode(Request $request)
    {
        // Debug logging
        Log::info('Verification code request received', [
            'all_data' => $request->all(),
            'verification_code' => $request->input('verification_code'),
            'email' => $request->input('email'),
            'method' => $request->method(),
            'has_recaptcha' => $request->has('g-recaptcha-response')
        ]);

        // Validate input
        $validated = $request->validate([
            'email' => 'required|email',
            'verification_code' => 'required|string|min:6|max:6|regex:/^[0-9]{6}$/',
        ], [
            'verification_code.required' => 'Please enter the 6-digit verification code.',
            'verification_code.min' => 'Verification code must be exactly 6 digits.',
            'verification_code.max' => 'Verification code must be exactly 6 digits.',
            'verification_code.regex' => 'Verification code must contain only numbers.',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user) {
            Log::warning('Verification failed: User not found', [
                'email' => $validated['email'],
                'ip' => $request->ip()
            ]);
            return back()->withErrors(['email' => 'User not found.'])->withInput();
        }

        Log::info('User found for code verification', [
            'user_id' => $user->id,
            'email' => $user->email,
            'stored_code' => $user->email_verification_code,
            'provided_code' => $validated['verification_code'],
            'is_verified' => $user->hasVerifiedEmail(),
            'is_active' => $user->is_active
        ]);

        if ($user->hasVerifiedEmail()) {
            // Auto-login if already verified
            Auth::login($user);
            return redirect()->route('home')->with('success', 'Welcome back! Your email is already verified.');
        }

        // Check if verification code matches
        if (!$user->isValidVerificationCode($validated['verification_code'])) {
            Log::warning('Verification failed: Invalid code', [
                'user_id' => $user->id,
                'email' => $user->email,
                'expected_code' => $user->email_verification_code,
                'provided_code' => $validated['verification_code'],
                'ip' => $request->ip()
            ]);
            return back()->withErrors(['verification_code' => 'Invalid verification code. Please check and try again.'])->withInput();
        }

        // Mark email as verified
        $user->markEmailAsVerified();

        Log::info('User email verified via code', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        // Auto-login after successful verification
        Auth::login($user);

        return redirect()->route('home')->with('success', 'Email verified successfully! Welcome to JV Prophecy Manager!');
    }

    /**
     * Resend the email verification notification.
     */
    public function resend(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'User not found.']);
        }

        if ($user->hasVerifiedEmail()) {
            return back()->with('success', 'Email is already verified.');
        }

        // Generate new verification code and token
        $user->generateEmailVerification();

        // Send verification email
        $user->notify(new EmailVerificationNotification($user));

        Log::info('Verification email resent', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip()
        ]);

        return back()->with('success', 'Verification email sent! Please check your inbox.');
    }
}