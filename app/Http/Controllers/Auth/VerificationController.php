<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use App\Helpers\Lang;

use App\Models\User;

class VerificationController extends Controller
{
    /**
    * Auth controller
    */
    public function __construct() {}

    /**
    * User actions
    * Login, Logout, Register, RestorePass
    */
    public function verify($user_id, Request $request) {
        if (!$request->hasValidSignature()) {
            return \Response::json([
                'response' => 4001,
                'error' => Lang::get("api.email.verify.notvalid"),
                'time' => date('H:i', time()) 
            ]);
        }
    
        $user = User::findOrFail($user_id);
    
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }
    
        return \Response::json([
            'response' => 200,
            'message' => Lang::get("api.email.verify.success"),
            'time' => date('H:i', time()) 
        ]);
    }
    
    public function resend() {
        if (auth()->user()->hasVerifiedEmail()) {
            return \Response::json([
                'response' => 4002,
                'error' => Lang::get("api.email.verify.already"),
                'time' => date('H:i', time()) 
            ]);
        }
    
        auth()->user()->sendEmailVerificationNotification();
    
        return \Response::json([
            'response' => 200,
            'message' => Lang::get("api.email.verify.resend"),
            'time' => date('H:i', time()) 
        ]);
    }
}