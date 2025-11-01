<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\UserRequestValidation;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class EmailVerificationController extends Controller
{
    public function EmailVerificationRequest(EmailVerificationRequest $request){
        $request->fulfill();

        return reponse()->json([
            'status' => 'success'
        ],200);
    }

    public function ResendEmailVarification(Request $request){
        $request->sendEmailVerificationNotification();

        return reponse()->json([
            'message' => 'Verification link sent'
        ], 200);
    }
}
