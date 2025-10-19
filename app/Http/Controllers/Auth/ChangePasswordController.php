<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChangePasswordController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the change password form.
     */
    public function showChangePasswordForm()
    {
        return view('auth.passwords.change');
    }

    /**
     * Change the user's password.
     */
    public function changePassword(Request $request)
    {
        $user = Auth::user();

        Log::info('Password change attempt', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip(),
        ]);

        // Validate the request
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed|different:current_password',
        ], [
            'current_password.required' => 'Please enter your current password.',
            'new_password.required' => 'Please enter a new password.',
            'new_password.min' => 'New password must be at least 8 characters long.',
            'new_password.confirmed' => 'New password confirmation does not match.',
            'new_password.different' => 'New password must be different from current password.',
        ]);

        if ($validator->fails()) {
            Log::warning('Password change validation failed', [
                'user_id' => $user->id,
                'errors' => $validator->errors()->toArray()
            ]);
            
            return back()
                ->withErrors($validator)
                ->with('error', 'Please check the form for errors.');
        }

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            Log::warning('Password change failed - incorrect current password', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
            
            return back()
                ->withErrors(['current_password' => 'The current password is incorrect.'])
                ->with('error', 'The current password you entered is incorrect.');
        }

        // Update the password
        $user->password = Hash::make($request->new_password);
        $user->save();

        Log::info('Password changed successfully', [
            'user_id' => $user->id,
            'email' => $user->email
        ]);

        // Log out from all other devices (optional - uncomment if needed)
        // Auth::logoutOtherDevices($request->new_password);

        return redirect()->route('change-password')
            ->with('success', 'Your password has been changed successfully!');
    }
}

