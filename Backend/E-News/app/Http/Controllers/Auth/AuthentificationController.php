<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequestValidation;
use App\Models\User;
/**
 * @OA\Info(title="API Authentication", version="1.0")
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer"
 * )
 */
class AuthentificationController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Inscription utilisateur",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Utilisateur créé avec succès"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function Register(UserRequestValidation $request){
        $credentials = $request->validated();
        $user = User::create($credentials);

        
        if($user){
            event(new Registered($user));
            return response()->json([
                'message' => 'User create Successfully'
            ], 200);
        }else{
            return response()->json([
                    'message' => 'User creation failed'
                ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Connexion utilisateur",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Connexion réussie"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Échec de la connexion"
     *     )
     * )
     */
    public function Login(UserRequestValidation $request){
        $credentials = $request->validated();

        if(Auth::attempt($credentials)){
            $request->session()->regenerate();

            return response()->json([
                'message' => 'Login Successfully'
            ], 200);
        }else{
            return response()->json([
                'message' => 'Login failled'
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Déconnexion utilisateur",
     *     tags={"Authentication"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Déconnexion réussie"
     *     )
     * )
     */
    public function Logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Logout Successfully'
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/confirm-password",
     *     summary="Confirmation du mot de passe",
     *     tags={"Authentication"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"password"},
     *             @OA\Property(property="password", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Mot de passe confirmé"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Mot de passe incorrect"
     *     )
     * )
     */
    public function ConfirmPassword(Request $request){
        if(!Hash::check($request->password, $request->user()->password)){
            return response()->json([
                'message' => 'The provided password does not match our records.'
            ], 500);
        }

        $request->session()->passwordConfirmed();

        return response()->json([
            'message' => 'Password confirm'
        ], 200);
    }
}