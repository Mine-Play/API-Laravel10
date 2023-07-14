<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Helpers\Lang;

class LoginController extends Controller
{
    /**
    * Auth controller
    */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
    * User actions
    * Login, Logout, Register, RestorePass
    */
    public function login()
    {
        $credentials = request(['name', 'password']);
        $user = User::where('name', $credentials["name"])->first();
        return var_dump($user->tokens()->get()->pluck('token'));
        if (!$user) {
            $user = User::where('email', $credentials["name"])->first();
            if(!$user || !Hash::check($credentials["password"], $user->password)){
                return response()->json([
                    'response' => 401,
                    'error' => Lang::get('login.errors.credentials'),
                    'time' => date('H:i', time()) 
                ]);
            }
        }

        return $this->respondWithToken($user->createToken("access_token")->plainTextToken, $credentials["name"]);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
    protected function respondWithToken($token, $login)
    {
        return response()->json([
            'response' => 200,
            'message' => Lang::get('login.messages.successful', ["nickname" => $login]),
                'access_token' => $token,
                'token_type' => 'bearer',
                'time' => date('H:i', time()) 
        ]);
    }
}