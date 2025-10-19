<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\UnicodeService;

class UnicodeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Set Unicode headers for proper multi-language support
        UnicodeService::setUnicodeHeaders();
        
        $response = $next($request);
        
        // Ensure response headers are set for Unicode content
        if (method_exists($response, 'header')) {
            $response->header('Content-Type', 'text/html; charset=UTF-8');
        }
        
        return $response;
    }
}
