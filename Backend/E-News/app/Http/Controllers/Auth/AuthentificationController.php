<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequestValidation;
use App\Http\Requests\LoginRequestValidation;
use App\Models\User;

class AuthentificationController extends Controller
{
    /**
     * Inscription d'un nouvel utilisateur
     * Envoie automatiquement un email de vérification
     */
    public function register(UserRequestValidation $request)
    {
        $credentials = $request->validated();
        
        $user = User::create([
            'name' => $credentials['name'],
            'email' => $credentials['email'],
            'password' => Hash::make($credentials['password']),
            'categories_user' => $credentials['categories_user'] ?? [],
        ]);

        // Déclencher l'événement d'inscription (envoie automatiquement l'email de vérification)
        event(new Registered($user));
        
        // Créer un token pour l'utilisateur
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
            'response' => 'Registration successful. Please verify your email address.',
            'email_verified' => $user->hasVerifiedEmail(),
        ], 201);
    }

    /**
     * Connexion d'un utilisateur
     * Vérifie si l'email est vérifié avant de permettre la connexion
     */
    public function login(LoginRequestValidation $request)
    {
        $credentials = $request->validated();

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'response' => 'Invalid credentials'
            ], 401);
        }

        // Vérifier si l'email est vérifié
        if (!$user->hasVerifiedEmail()) {
            return response()->json([
                'response' => 'Please verify your email address before logging in.',
                'email_verified' => false,
                'user_id' => $user->id,
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'access_token' => $token,
            'token_type' => 'Bearer',
            'response' => 'Login successfully',
            'user' => $user,
            'email_verified' => true,
        ], 200);
    }

    /**
     * Déconnexion - Révoque le token actuel
     */
    public function logout(Request $request)
    {   
        try {
                    // Révoquer uniquement le token utilisé pour cette requête
                    $request->user()->currentAccessToken()->delete();

                    return response()->json([
                        'success' => true,
                        'response' => 'Logout successfully'
                    ], 200);
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'response' => 'Logout failed',
                        'error' => $e->getMessage()
                    ], 500);
                }
       }

    /**
     * Déconnexion complète - Révoque tous les tokens
     */
    public function logoutAll(Request $request)
    {
        // Révoquer tous les tokens de l'utilisateur
        $request->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'response' => 'All sessions logged out successfully'
        ], 200);
    }

    /**
     * Vérifier le mot de passe (pour actions sensibles)
     */
    public function confirmPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8',
        ]);

        if (!Hash::check($request->password, $request->user()->password)) {
            return response()->json([
                'success' => false,
                'response' => 'The provided password does not match our records.'
            ], 422);
        }

        return response()->json([
            'success' => true,
            'response' => 'Password confirmed successfully'
        ], 200);
    }

    /**
     * Route de fallback pour authentification requise
     */
    public function loginView()
    {
        return response()->json([
            'success' => false,
            'response' => 'Authentication required'
        ], 401);
    }

    /**
     * Obtenir l'utilisateur authentifié
     */
    public function user(Request $request)
    {
        return response()->json([
            'success' => true,
            'response' => 'User retrieved successfully',
            'user' => $request->user()
        ], 200);
    }

    /**
     * Rafraîchir le token (créer un nouveau token)
     * Note: Sanctum ne supporte pas les refresh tokens nativement,
     * on crée donc un nouveau token et on supprime l'ancien
     */
    public function refreshToken(Request $request)
    {
        try {
            // Récupérer le token actuel
            $currentToken = $request->user()->currentAccessToken();
            
            // Créer un nouveau token
            $newToken = $request->user()->createToken('auth_token')->plainTextToken;
            
            // Supprimer l'ancien token
            if ($currentToken) {
                $currentToken->delete();
            }

            return response()->json([
                'success' => true,
                'access_token' => $newToken,
                'token_type' => 'Bearer',
                'response' => 'Token refreshed successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'response' => 'Token refresh failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}