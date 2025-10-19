<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        // Handle 419 Token Mismatch Exception (Page Expired)
        if ($exception instanceof TokenMismatchException) {
            return $this->handle419Error($request);
        }

        // Handle 419 HTTP Exception
        if ($exception instanceof HttpException && $exception->getStatusCode() === 419) {
            return $this->handle419Error($request);
        }

        return parent::render($request, $exception);
    }

    /**
     * Handle 419 Page Expired errors
     */
    protected function handle419Error($request)
    {
        // For AJAX requests, return JSON response
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'error' => 'Session expired',
                'message' => 'Your session has expired. Please refresh the page.',
                'redirect' => route('home'),
                'status' => 419
            ], 419);
        }

        // For regular requests, show custom 419 page
        return response()->view('errors.419', [], 419);
    }
}
