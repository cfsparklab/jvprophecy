<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LanguageController extends Controller
{
    /**
     * Switch user language preference.
     */
    public function switch(Request $request)
    {
        $request->validate([
            'language' => 'required|string|in:en,ta,kn,te,ml,hi',
        ]);

        $language = $request->language;

        // Update user's preferred language if authenticated
        if (Auth::check()) {
            $user = Auth::user();
            $user->preferred_language = $language;
            $user->save();
        }

        // Store in session for guest users
        session(['preferred_language' => $language]);

        return back()->with('success', 'Language preference updated successfully.');
    }
}
