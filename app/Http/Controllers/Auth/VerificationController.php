<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\Lang;

use App\Mail\VerifyEmail;
use App\Models\User;
use Carbon\Carbon;

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
    public function verify(Request $request) {
        $validator = Validator::make($request->all(), [
            'pin' => ['required', 'integer', 'min:10000', 'max:99999'],
        ]);
    
        if ($validator->fails()) {
            $errors = $validator->errors();
            return \Response::json([
                'response' => 4001,
                'error' => $errors->all(':message')[0],
                'time' => date('H:i', time()) 
            ]);
        }
        $select = DB::connection('Site')->table('Email_confirmations')
            ->where('email', Auth::user()->email)
            ->where('pin', $request->pin)
            ->whereDate('created_at', '<=', Carbon::now()->toDateTimeString());
        if ($select->get()->isEmpty()) {
            return \Response::json([
                'response' => 4001,
                'error' => Lang::get("api.email.verify.notvalid"),
                'time' => date('H:i', time()) 
            ]);
        }
    
        $select = DB::connection('Site')->table('Email_confirmations')
            ->where('email', Auth::user()->email)
            ->where('pin', $request->pin)
            ->delete();
    
        $user = User\Instance::find(Auth::user()->id);
        $user->email_verified_at = Carbon::now()->toDateTimeString();
        $user->save();
    
        return \Response::json([
            'response' => 200,
            'message' => Lang::get("api.email.verify.success"),
            'time' => date('H:i', time()) 
        ]);
    }
    
    public function resend(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);
    
        if ($validator->fails()) {
            $errors = $validator->errors();
            return \Response::json([
                'response' => 4001,
                'error' => $errors->all(':message')[0],
                'time' => date('H:i', time()) 
            ]);
        }
    
        if (auth()->user()->hasVerifiedEmail()) {
            return \Response::json([
                'response' => 4002,
                'error' => Lang::get("api.email.verify.already"),
                'time' => date('H:i', time()) 
            ]);
        }
        $select = DB::connection('Site')->table('Email_confirmations')->where('email', Auth::user()->email)->first();
        if($select != NULL){
            DB::connection('Site')->table('Email_confirmations')->where('email', Auth::user()->email)->delete();
        }
        $pin = random_int(10000, 99999);
        $password_reset = DB::connection('Site')->table('Email_confirmations')->insert([
            'email' => $request->all()['email'],
            'pin' =>  $pin
        ]);
    
        if ($password_reset) {
            Mail::to($request->all()['email'])->send(new VerifyEmail($pin));
            return \Response::json([
                'response' => 200,
                'message' => Lang::get("api.email.verify.resend"),
                'time' => date('H:i', time()) 
            ]);    
        }
    }
}