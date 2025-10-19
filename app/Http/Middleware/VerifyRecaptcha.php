<?php

namespace App\Http\Middleware;

use App\Services\RecaptchaService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class VerifyRecaptcha
{
    /**
     * The reCAPTCHA service instance.
     */
    protected RecaptchaService $recaptchaService;

    /**
     * Create a new middleware instance.
     */
    public function __construct(RecaptchaService $recaptchaService)
    {
        $this->recaptchaService = $recaptchaService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $action = 'submit'): Response
    {
        // Skip verification if reCAPTCHA is disabled
        if (!$this->recaptchaService->isEnabled()) {
            return $next($request);
        }

        // Only verify POST requests
        if (!$request->isMethod('POST')) {
            return $next($request);
        }

        // Get the reCAPTCHA token from the request
        $token = $request->input('g-recaptcha-response');

        if (empty($token)) {
            throw ValidationException::withMessages([
                'recaptcha' => ['Please complete the reCAPTCHA verification.'],
            ]);
        }

        // Verify the token
        $result = $this->recaptchaService->verify($token, $action, $request->ip());

        if (!$result['success']) {
            // Log the failed verification
            logger()->warning('reCAPTCHA verification failed', [
                'error' => $result['error'] ?? 'Unknown error',
                'score' => $result['score'] ?? 0,
                'action' => $action,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
            ]);

            // Determine the error message based on the failure reason
            $errorMessage = $this->getErrorMessage($result);

            throw ValidationException::withMessages([
                'recaptcha' => [$errorMessage],
            ]);
        }

        // Log successful verification
        logger()->info('reCAPTCHA verification successful', [
            'score' => $result['score'] ?? 0,
            'action' => $action,
            'ip_address' => $request->ip(),
            'hostname' => $result['hostname'] ?? null,
        ]);

        // Add the verification result to the request for potential use in controllers
        $request->merge(['recaptcha_result' => $result]);

        return $next($request);
    }

    /**
     * Get the appropriate error message based on the verification result.
     */
    protected function getErrorMessage(array $result): string
    {
        $error = $result['error'] ?? 'Unknown error';
        $score = $result['score'] ?? 0;

        switch ($error) {
            case 'Score too low':
                return 'Security verification failed. Please try again or contact support if the problem persists.';
            
            case 'Action mismatch':
                return 'Invalid security token. Please refresh the page and try again.';
            
            case 'Verification service unavailable':
                return 'Security verification service is temporarily unavailable. Please try again later.';
            
            default:
                if (isset($result['error_codes']) && is_array($result['error_codes'])) {
                    $errorCodes = $result['error_codes'];
                    
                    if (in_array('timeout-or-duplicate', $errorCodes)) {
                        return 'Security token has expired. Please refresh the page and try again.';
                    }
                    
                    if (in_array('invalid-input-response', $errorCodes)) {
                        return 'Invalid security token. Please refresh the page and try again.';
                    }
                    
                    if (in_array('missing-input-response', $errorCodes)) {
                        return 'Please complete the security verification.';
                    }
                }
                
                return 'Security verification failed. Please try again.';
        }
    }
}
