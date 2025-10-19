<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAuthForDownload
{
    /**
     * Handle an incoming request for download routes.
     * 
     * Instead of redirecting to login (which causes HTML to be downloaded),
     * show a proper error page or JSON response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            // Check if request expects JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Unauthenticated',
                    'message' => 'Please login to download PDFs',
                    'redirect' => route('login')
                ], 401);
            }
            
            // For regular requests, return a friendly HTML page instead of redirecting
            return response()->view('errors.auth-required', [
                'message' => 'Your session has expired. Please login again to download PDFs.',
                'login_url' => route('login'),
                'return_url' => $request->fullUrl()
            ], 401);
        }

        return $next($request);
    }
}
