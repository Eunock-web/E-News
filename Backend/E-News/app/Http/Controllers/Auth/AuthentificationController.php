<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserRequestValidation;
use App\Models\User;

class AuthentificationController extends Controller
{
    public function Register(UserRequestValidation $request){
        
        $credentials = $request->validated();

        $user = User::create($credentials);

        if($user){
            return response()->json([
                'message' => 'User create Successfully'
            ], 201);
        }
    }



    public function Login(UserRequestValidation $request): RedirectResponse{

        $credentials = $request->validated();

        if(Auth::attempt($credentials)){
            $request -> session() -> regenarate();

            return response()->json([
                'message' => 'Login Successfully'
            ], 201);

        }else{
            return response()->json([
                'message' => 'Login failled'
            ], 500);
        }
    }


    public function Logout(Request $request): RedirectResponse{

        Auth::Logout();

        $request -> session()-> invalidate();

        $request -> session() -> regenerateToken();

        return response()->json([
            'message' => 'Logout Successfully'
        ], 201);
    }

    public function ConfirmPassword(Request $request){
        if( !Hash::check($request->password, $request->user()->password) ){
            return response()->json([
                'message' => 'The provided password does not match our records.'
            ], 500);
        }

        $request->session()->passwordConfirmed();

        return response()->json([
            'message' => 'Password confirm'
        ], 201);
    }
}
