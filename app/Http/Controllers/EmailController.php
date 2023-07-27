<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use App\Helpers\Lang;

use App\Models\User;

class EmailController extends Controller
{
    /**
    * Auth controller
    */
    public function __construct() {}

    /**
    * User actions
    * Login, Logout, Register, RestorePass
    */
    public function verify(Request $request)
    {
        $user = User::find($request->route('id'));

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'status' => 200,
                'message' => Lang::get('api.email.verify.already'),
                'time' => date('H:i', time()) 
            ]);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }
        
        return response()->json([
            'status' => 200,
            'message' => Lang::get('api.email.verify.success'),
            'time' => date('H:i', time()) 
        ]);
    }
}