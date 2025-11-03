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
    public function ForgotPassword(Request $request){
        $request->validate(['email' => 'required | email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if($status === Password::ResetLinkSent()){
                return reponse()->json([
                    'status' => __($status)
                ]);
        }else{
                return reponse()->json([
                    'email' => __($status)
                ]);
        }
    }

    public function ResetPassword(Request $request) {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if($status === Password::PasswordReset){
            return response()->json([
                'status' => __($status)
            ]);
        }else{
            return response()->json([
                'email' => __($status)
            ]);
        }
    }

}

