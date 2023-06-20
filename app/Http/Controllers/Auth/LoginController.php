<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
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
        if (Auth::check()) {
            return response()->json([
                'response' => 403,
                'error' => Lang::get('login.errors.already'),
                'time' => date('H:i', time()) 
            ]);
        }
        $credentials = request(['name', 'password']);
        if (! $token = auth()->attempt($credentials)) {
            return response()->json([
                'response' => 401,
                'error' => Lang::get('login.errors.credentials'),
                'time' => date('H:i', time()) 
            ]);
        }

        return $this->respondWithToken($token, $credentials["name"]);
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
                'expires_in' => auth()->factory()->getTTL() * 60,
                'time' => date('H:i', time()) 
        ]);
    }
}