<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Helpers\Lang;
use App\Helpers\Response;

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
        
        return Response::success();
    }
}