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
     */
    public function EmailVerificationRequest(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        // Vérifier que l'URL est signée et valide
        if (!URL::hasValidSignature($request)) {
            return response()->json([
                'message' => 'Invalid verification link'
            ], 403);
        }

        // Vérifier que le hash correspond à l'email de l'utilisateur
        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return response()->json([
                'message' => 'Invalid verification link'
            ], 403);
        }

        // Vérifier si l'email est déjà vérifié
        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email already verified'
            ], 200);
        }

        // Marquer l'email comme vérifié
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return response()->json([
            'message' => 'Email verified successfully',
        ], 200);
    }

    /**
     * Renvoyer l'email de vérification
     */
    public function ResendEmailVarification(Request $request)
    {
        $user = $request->user();
        
        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email already verified'
            ], 200);
        }

        $user->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'Verification link sent',
        ], 200);
    }

    public function status(Request $request){
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email verified successfully',
            ], 200);
        }
        return response()->json([
            'message' => 'Email not verified',
        ], 400);
    }
}
