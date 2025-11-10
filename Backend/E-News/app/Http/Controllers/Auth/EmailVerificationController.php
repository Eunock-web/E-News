<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\UserRequestValidation;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\URL;

class EmailVerificationController extends Controller
{
    /**
     * Vérifier l'email via lien avec id et hash
     * Cette route peut être appelée directement depuis le frontend (lien dans l'email)
     */
    public function EmailVerificationRequest(Request $request, $id, $hash)
    {
        try {
            $user = User::findOrFail($id);

            // Vérifier que l'URL est signée et valide
            if (!URL::hasValidSignature($request)) {
                return response()->json([
                    'success' => false,
                    'response' => 'Invalid or expired verification link',
                    'error' => 'signature_invalid'
                ], 403);
            }

            // Vérifier que le hash correspond à l'email de l'utilisateur
            if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
                return response()->json([
                    'success' => false,
                    'response' => 'Invalid verification link',
                    'error' => 'hash_mismatch'
                ], 403);
            }

            // Vérifier si l'email est déjà vérifié
            if ($user->hasVerifiedEmail()) {
                return response()->json([
                    'success' => true,
                    'response' => 'Email already verified',
                    'email_verified' => true,
                    'user' => $user
                ], 200);
            }

            // Marquer l'email comme vérifié
            if ($user->markEmailAsVerified()) {
                event(new Verified($user));
            }

            return response()->json([
                'success' => true,
                'response' => 'Email verified successfully',
                'email_verified' => true,
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'response' => 'Verification failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Renvoyer l'email de vérification
     * L'utilisateur doit être authentifié pour demander un nouveau lien
     */
    public function ResendEmailVarification(Request $request)
    {
        try {
            $user = $request->user();
            
            if ($user->hasVerifiedEmail()) {
                return response()->json([
                    'success' => true,
                    'response' => 'Email already verified',
                    'email_verified' => true
                ], 200);
            }

            // Envoyer la notification de vérification
            $user->sendEmailVerificationNotification();

            return response()->json([
                'success' => true,
                'response' => 'Verification link sent to your email',
                'email_verified' => false
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'response' => 'Failed to send verification email',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Vérifier le statut de vérification de l'email
     */
    public function status(Request $request)
    {
        try {
            $user = $request->user();
            $isVerified = $user->hasVerifiedEmail();
            
            return response()->json([
                'success' => true,
                'response' => $isVerified ? 'Email verified' : 'Email not verified',
                'email_verified' => $isVerified,
                'user' => $user
            ], $isVerified ? 200 : 200); // Toujours 200, on utilise email_verified pour le statut
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'response' => 'Failed to check verification status',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
