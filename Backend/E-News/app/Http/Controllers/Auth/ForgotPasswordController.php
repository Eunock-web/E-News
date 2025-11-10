<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class ForgotPasswordController extends Controller
{
    /**
     * Envoyer le lien de réinitialisation du mot de passe
     * Cette route est publique et peut être appelée sans authentification
     */
    public function forgotPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email'
            ]);

            $status = Password::sendResetLink(
                $request->only('email')
            );

            // Toujours retourner le même message pour la sécurité (ne pas révéler si l'email existe)
            // Laravel Password::sendResetLink() ne révélera pas si l'email existe ou non
            return response()->json([
                'success' => true,
                'response' => 'If that email address exists in our system, we have sent a password reset link.',
                'status' => 'sent'
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'response' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'response' => 'Failed to send password reset link',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Réinitialiser le mot de passe
     * Cette route est publique et peut être appelée sans authentification
     */
    public function resetPassword(Request $request)
    {
        try {
            $request->validate([
                'token' => 'required|string',
                'email' => 'required|email|exists:users,email',
                'password' => 'required|string|min:8|confirmed',
            ], [
                'email.exists' => 'We could not find a user with that email address.',
                'password.confirmed' => 'The password confirmation does not match.',
                'password.min' => 'The password must be at least 8 characters.'
            ]);

            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->forceFill([
                        'password' => Hash::make($password)
                    ])->setRememberToken(Str::random(60));

                    $user->save();

                    event(new PasswordReset($user));
                    
                    // Optionnel : Révoquer tous les tokens existants pour sécurité
                    $user->tokens()->delete();
                }
            );

            if ($status === Password::PASSWORD_RESET) {
                return response()->json([
                    'success' => true,
                    'response' => 'Password reset successfully. You can now login with your new password.',
                    'status' => 'success'
                ], 200);
            }

            // Gérer les différents cas d'erreur
            $errorMessages = [
                Password::INVALID_TOKEN => 'This password reset token is invalid or has expired.',
                Password::INVALID_USER => 'We could not find a user with that email address.',
                Password::THROTTLED => 'Please wait before retrying.',
            ];

            return response()->json([
                'success' => false,
                'response' => $errorMessages[$status] ?? 'Password reset failed',
                'status' => 'error',
                'error' => $status
            ], 422);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'response' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'response' => 'Failed to reset password',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

