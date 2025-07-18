<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ServiceController;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('guest')->group(function () {
    Route::post('/register', [RegisteredUserController::class, 'store'])
    ->name('register');

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->name('login');

    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->name('password.email');

    Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->name('password.store');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware('throttle:6,1')
    ->name('verification.send');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

    Route::apiResource('customers', CustomerController::class);
    
    Route::get('/appointments', [AppointmentController::class, 'index']);
    Route::post('/appointment', [AppointmentController::class, 'store'])->middleware('throttle:5,1');
    Route::put('/appointment/{id}', [AppointmentController::class, 'update']);
    Route::delete('/appointment/{id}', [AppointmentController::class, 'destroy']);

    Route::apiResource('categories', CategoryController::class);

    Route::apiResource('services', ServiceController::class);

    Route::apiResource('organizations', OrganizationController::class);

    Route::get('/payments', [PaymentController::class, 'index']);
    Route::post('/payment', [PaymentController::class, 'store'])->middleware('throttle:3,1');
    Route::put('/payment/{id}', [PaymentController::class, 'update']);
    Route::delete('/payment/{id}', [PaymentController::class, 'destroy']);
});

Route::get('/redis-test', function () {
    Cache::store('redis')->put('test-key', 'Hello Redis', 10); // Store for 10 seconds

    $value = Cache::store('redis')->get('test-key');

    return response()->json([
        'status' => $value ? 'success' : 'failed',
        'value' => $value,
    ]);
});


