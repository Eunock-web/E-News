<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthentificationController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\News\NewsController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('news')->group(function () {
    Route::get('/categories', [NewsController::class, 'ListeCategories']);
    Route::get('/articles', [NewsController::class, 'ArticlesInfos']);
})->middleware('auth:sanctum');

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthentificationController::class, 'Register'])->name('auth.register');
    Route::post('/login', [AuthentificationController::class, 'Login'])->name('auth.login');
    Route::post('/logout', [AuthentificationController::class, 'Logout'])->name('logout');
    
    Route::prefix('email')->group(function () {
        Route::get('/verify/{id}/{hash}', [EmailVerificationController::class, 'EmailVerificationRequest'])->middleware(['auth', 'signed'])->name('verification.verify');
        Route::post('/verification-notification', [EmailVerificationController::class, 'ResendEmailVerification'])->middleware(['auth', 'throttle:6,1'])->name('verification.send');
    });
});
Route::post('/forgot-password',[ForgotPasswordController::class, 'ForgotPassword'])->middleware('guest')->name('password.email');
Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'ResetPassword'])->middleware('guest')->name('password.update');
