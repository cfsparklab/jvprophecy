<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProphecyController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Api\ProphecyController as ApiProphecyController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Default page - redirect to login or home based on auth status
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('home');
    }
    return redirect()->route('login');
})->name('welcome');

// Authenticated user routes
Route::middleware(['auth'])->group(function () {
    // Home page with date selection
    Route::get('/home', [PublicController::class, 'index'])->name('home');
    
    // Change Password
    Route::get('/change-password', [ChangePasswordController::class, 'showChangePasswordForm'])->name('change-password');
    Route::post('/change-password', [ChangePasswordController::class, 'changePassword'])->name('change-password.update');
    
    // Prophecy viewing routes
    Route::get('/prophecies/date', [PublicController::class, 'showPropheciesByDate'])->name('prophecies.by-date');
    Route::get('/prophecies/{id}', [PublicController::class, 'showProphecy'])->name('prophecies.show');
    
    // Search
    Route::get('/search', [PublicController::class, 'search'])->name('search');
});

// PDF Download routes (require auth but handle expired sessions gracefully)
Route::middleware(['auth.download'])->group(function () {
    Route::get('/prophecies/{id}/download', [PublicController::class, 'downloadProphecy'])->name('prophecies.download');
    Route::get('/prophecies/{id}/download-pdf', [PublicController::class, 'downloadUploadedProphecyPdf'])->name('prophecies.download.pdf');
    Route::get('/prophecies/{id}/print', [PublicController::class, 'printProphecy'])->name('prophecies.print');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

// Login routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('recaptcha:login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Registration routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->middleware('recaptcha:register');

// Email verification routes
Route::get('/email/verify', [EmailVerificationController::class, 'notice'])->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify');
Route::get('/email/verify-code', [EmailVerificationController::class, 'show'])->name('verification.show');
Route::post('/email/verify-code', [EmailVerificationController::class, 'verifyCode'])->middleware('recaptcha:verify_email')->name('verification.verify-code');
Route::post('/email/resend', [EmailVerificationController::class, 'resend'])->middleware('recaptcha:resend_email')->name('verification.resend');

// Password reset routes
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->middleware('recaptcha:forgot_password')->name('password.email');
Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->middleware('recaptcha:reset_password')->name('password.update');

// Google OAuth Routes
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Language switching
Route::post('/language/switch', [LanguageController::class, 'switch'])->name('language.switch');

// Test route for PDF generation (bypasses auth)
Route::get('/test-pdf/{id}', [PublicController::class, 'testPdfGeneration'])->name('test.pdf');

// Web2PDF routes
Route::get('/prophecy/{id}/web-print', [PublicController::class, 'showWebPrintView'])->name('prophecy.web-print');
Route::get('/prophecy/{id}/download-web2pdf', [PublicController::class, 'downloadProphecyWeb2Pdf'])->name('prophecy.download.web2pdf');
Route::get('/prophecy/{id}/download-jpeg', [PublicController::class, 'downloadProphecyJpeg'])->name('prophecy.download.jpeg');

// Test route for email verification debugging
Route::get('/test-verification/{id}', function($id) {
    $user = App\Models\User::find($id);
    if (!$user) {
        return response()->json(['error' => 'User not found']);
    }
    
    return response()->json([
        'user_id' => $user->id,
        'email' => $user->email,
        'email_verified_at' => $user->email_verified_at,
        'is_active' => $user->is_active,
        'has_verified_email' => $user->hasVerifiedEmail(),
        'email_verification_token' => $user->email_verification_token,
        'email_verification_code' => $user->email_verification_code,
        'verification_code_expires_at' => $user->verification_code_expires_at,
        'hash' => sha1($user->email)
    ]);
})->name('test.verification');

// API Routes
Route::prefix('api')->group(function () {
    Route::post('/prophecies/{id}/increment-view', [ApiProphecyController::class, 'incrementView']);
    Route::post('/log-activity', [ApiProphecyController::class, 'logActivity']);
    
    // Session management
    Route::get('/session-check', function() {
        return response()->json([
            'status' => 'active',
            'csrf_token' => csrf_token(),
            'timestamp' => now()->toISOString()
        ]);
    })->name('api.session.check');
    
    // Admin API Routes (protected)
    Route::middleware(['auth', 'role:super_admin,admin,editor'])->prefix('admin')->group(function () {
        Route::get('/dashboard-stats', [App\Http\Controllers\Api\AdminApiController::class, 'getDashboardStats']);
        Route::get('/system-status', [App\Http\Controllers\Api\AdminApiController::class, 'getSystemStatus']);
        Route::get('/user-activity', [App\Http\Controllers\Api\AdminApiController::class, 'getUserActivityTimeline']);
        Route::get('/prophecy-stats', [App\Http\Controllers\Api\AdminApiController::class, 'getProphecyStats']);
        Route::get('/search', [App\Http\Controllers\Api\AdminApiController::class, 'globalSearch']);
    });
});


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:super_admin,admin,editor'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/data', [DashboardController::class, 'getData'])->name('dashboard.data');
    
    // Prophecy Management
    Route::resource('prophecies', ProphecyController::class);
    Route::post('prophecies/{prophecy}/publish', [ProphecyController::class, 'publish'])->name('prophecies.publish');
    Route::post('prophecies/{prophecy}/unpublish', [ProphecyController::class, 'unpublish'])->name('prophecies.unpublish');
    
    // Translation Management
    Route::get('prophecies/{prophecy}/translations', [ProphecyController::class, 'translations'])->name('prophecies.translations');
    Route::post('prophecies/{prophecy}/translations', [ProphecyController::class, 'storeTranslation'])->name('prophecies.translations.store');
    Route::get('prophecies/{prophecy}/translations/{language}/edit', [ProphecyController::class, 'editTranslation'])->name('prophecies.translations.edit');
    Route::put('prophecies/{prophecy}/translations/{language}', [ProphecyController::class, 'updateTranslation'])->name('prophecies.translations.update');
    Route::delete('prophecies/{prophecy}/translations/{language}', [ProphecyController::class, 'deleteTranslation'])->name('prophecies.translations.delete');
    
    // Category Management
    Route::resource('categories', CategoryController::class);
    
    // User Management
    Route::resource('users', UserController::class);
    Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    
    // Security Logs Management
    Route::prefix('security-logs')->name('security-logs.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\SecurityLogController::class, 'index'])->name('index');
        Route::get('/{securityLog}', [App\Http\Controllers\Admin\SecurityLogController::class, 'show'])->name('show');
        Route::post('/{securityLog}/mark-reviewed', [App\Http\Controllers\Admin\SecurityLogController::class, 'markReviewed'])->name('mark-reviewed');
        Route::post('/bulk-mark-reviewed', [App\Http\Controllers\Admin\SecurityLogController::class, 'bulkMarkReviewed'])->name('bulk-mark-reviewed');
        Route::delete('/{securityLog}', [App\Http\Controllers\Admin\SecurityLogController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-delete', [App\Http\Controllers\Admin\SecurityLogController::class, 'bulkDelete'])->name('bulk-delete');
        Route::get('/export', [App\Http\Controllers\Admin\SecurityLogController::class, 'export'])->name('export');
    });
    
    // Settings Management
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('settings/backup', [SettingsController::class, 'backup'])->name('settings.backup');
    Route::post('settings/clear-cache', [SettingsController::class, 'clearCache'])->name('settings.clear-cache');
    
    // Advanced Analytics
    Route::prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('index');
        Route::get('/export', [App\Http\Controllers\Admin\AnalyticsController::class, 'export'])->name('export');
    });
    
    // System Monitoring
    Route::prefix('system')->name('system.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\SystemController::class, 'index'])->name('index');
        Route::post('/clear-cache', [App\Http\Controllers\Admin\SystemController::class, 'clearCache'])->name('clear-cache');
        Route::post('/optimize', [App\Http\Controllers\Admin\SystemController::class, 'optimize'])->name('optimize');
        Route::post('/backup', [App\Http\Controllers\Admin\SystemController::class, 'backup'])->name('backup');
        Route::get('/logs', [App\Http\Controllers\Admin\SystemController::class, 'logs'])->name('logs');
    });
    
    // Bulk Operations
    Route::prefix('bulk')->name('bulk.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\BulkOperationsController::class, 'index'])->name('index');
        Route::post('/prophecies', [App\Http\Controllers\Admin\BulkOperationsController::class, 'bulkUpdateProphecies'])->name('prophecies');
        Route::post('/users', [App\Http\Controllers\Admin\BulkOperationsController::class, 'bulkUpdateUsers'])->name('users');
        Route::post('/import-prophecies', [App\Http\Controllers\Admin\BulkOperationsController::class, 'importProphecies'])->name('import-prophecies');
        Route::get('/export-prophecies', [App\Http\Controllers\Admin\BulkOperationsController::class, 'exportProphecies'])->name('export-prophecies');
        Route::post('/cleanup', [App\Http\Controllers\Admin\BulkOperationsController::class, 'cleanup'])->name('cleanup');
    });
});

