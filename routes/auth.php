<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    Route::get('reset-password', [PasswordResetLinkController::class, 'reset'])
                ->name('password.reset');
    Route::post('reset-password', [PasswordResetLinkController::class, 'resetPassword'])->name('password.reset');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('password.request');
    Route::post('update-password', [PasswordResetLinkController::class, 'updatePassword'])->name('password.update');
    Route::post('reset-password-user/{id}', [UserController::class, 'resetUser'])->name('password.reset.user');
});

// Route::middleware(['check.karyawan.guard'])->group(function () {
//     Route::get('/home', [HomeController::class, 'index'])->name('home');
//     Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
//                 ->name('logout');
// });

// Route::middleware(['check.user.guard'])->group(function () {
//     Route::get('/home', [HomeController::class, 'index'])->name('home');
//     Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
//                 ->name('logout');
// });
