<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
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
    public function logout()
    {
        
        auth()->logout();

        return response()->json([
            'respone' => 200,
            'message' => Lang::get('logout.message'),
            'time' => date('H:i', time()) 
        ]);
    }
}