<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\Lang;

class LogoutController extends Controller
{
    /**
    * Auth controller
    */
    public function __construct() {}

    /**
    * User actions
    * Login, Logout, Register, RestorePass
    */
    public function logout(Request $request)
    {
        
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'respone' => 200,
            'message' => Lang::get('logout.message'),
            'time' => date('H:i', time()) 
        ]);
    }
}