<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthentificationController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/register', [AuthentificationController::class, 'Register'])->name('auth.register');
Route::get('/login', [AuthentificationController::class, 'Login'])->name('auth.login');
Route::get('/logout', [AuthentificationController::class, 'Logout'])->name('logout');
Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'EmailVerificationRequest'])->middleware(['auth', 'signed'])->name('verification.verify');
Route::post('/email/verification-notification', [EmailVerificationController::class, 'ResendEmailVerification'])->middleware(['auth', 'throttle:6,1'])->name('verification.send');
Route::post('/forgot-password',[ForgotPasswordController::class, 'ForgotPassword'])->middleware('guest')->name('password.email');
Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');