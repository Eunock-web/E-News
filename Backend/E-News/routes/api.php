<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthentificationController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\News\NewsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Toutes les routes sont préfixées par /api automatiquement
*/

// ========================================
// Routes publiques (non authentifiées)
// ========================================

Route::prefix('auth')->group(function () {
    // Authentification de base
    Route::post('/register', [AuthentificationController::class, 'register']);
    Route::post('/login', [AuthentificationController::class, 'login']);
    
    // Réinitialisation de mot de passe (routes publiques)
    Route::post('/forgot-password', [ForgotPasswordController::class, 'forgotPassword'])
        ->name('password.email');
    
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])
        ->name('password.update');
    
    // Vérification d'email (route publique pour permettre le clic sur le lien)
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'EmailVerificationRequest'])
        ->middleware('signed')
        ->name('verification.verify');
    
    // Fallback pour routes protégées
    Route::get('/login', [AuthentificationController::class, 'loginView'])
        ->name('login');
});

// ========================================
// Routes protégées (authentification requise)
// ========================================
Route::middleware('auth:sanctum')->group(function () {
    
    // ---- Authentification ----
    Route::prefix('auth')->group(function () {
        // Déconnexion
        Route::post('/logout', [AuthentificationController::class, 'logout']);
        Route::post('/logout-all', [AuthentificationController::class, 'logoutAll']);
        
        // Utilisateur actuel
        Route::get('/user', [AuthentificationController::class, 'user']);
        
        // Rafraîchir le token
        Route::post('/refresh-token', [AuthentificationController::class, 'refreshToken']);
        
        // Confirmation de mot de passe
        Route::post('/confirm-password', [AuthentificationController::class, 'confirmPassword']);
        
        // Vérification d'email (routes protégées)
        Route::prefix('email')->group(function () {
            // Renvoyer l'email de vérification (utilisateur doit être authentifié)
            Route::post('/verification-notification', [EmailVerificationController::class, 'ResendEmailVarification'])
                ->middleware('throttle:6,1')
                ->name('verification.send');
            
            // Vérifier si l'email est déjà vérifié (utilisateur doit être authentifié)
            Route::get('/verification-status', [EmailVerificationController::class, 'status']);
        });
    });
    
    // ---- News ----
    Route::prefix('news')->group(function () {
        // Liste des catégories (avec cache Redis)
        Route::get('/categories', [NewsController::class, 'listeCategories']);
        
        // Articles avec pagination
        Route::get('/articles', [NewsController::class, 'articlesInfos']);
        
        // Articles filtrés par catégories favorites de l'utilisateur (avec cache Redis)
        Route::get('/articles/my-categories', [NewsController::class, 'articlesByUserCategories']);
        
        // Récupérer un article spécifique
        Route::get('/articles/{id}', [NewsController::class, 'retrieveArticle']);
    });
    });



// ========================================
// Route de fallback pour 404
// ========================================

Route::fallback(function () {
    return response()->json([
        'message' => 'Route not found'
    ], 404);
});