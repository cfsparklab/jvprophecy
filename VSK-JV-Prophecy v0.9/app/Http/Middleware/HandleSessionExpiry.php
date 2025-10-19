<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpFoundation\Response;

class HandleSessionExpiry
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            return $next($request);
        } catch (TokenMismatchException $e) {
            return $this->handleTokenMismatch($request);
        }
    }

    /**
     * Handle token mismatch exception
     */
    protected function handleTokenMismatch(Request $request)
    {
        // Log the session expiry
        \Log::info('Session expired for user', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'method' => $request->method()
        ]);

        // For AJAX requests
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'error' => 'Session expired',
                'message' => 'Your session has expired. Please refresh the page.',
                'redirect' => route('home'),
                'status' => 419
            ], 419);
        }

        // For regular requests, redirect to home with message
        return redirect()->route('home')
            ->with('warning', 'Your session has expired. Please log in again if needed.');
    }
}
