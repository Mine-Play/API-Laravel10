<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Helpers\Lang;
use App\Helpers\Response;

use App\Enums\Errors;

use App\Mail\VerifyEmail;
use App\Models\User;
use App\Models\Pin\Email as PIN;
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
            return Response::error(ERRORS::CLIENT_VALIDATION, $errors->all(':message')[0]);
        }

        $user = Auth::user();

        if (PIN::Check($user->email, $request->pin) == NULL) {
            return Response::error(ERRORS::CLIENT_CREDENTIALS);
        }
    
        PIN::Erase($user->email, $request->pin);
        $user->email_verified_at = Carbon::now()->toDateTimeString();
        $user->save();
    
        return Response::success();
    }
    
    public function resend(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);
    
        if ($validator->fails()) {
            $errors = $validator->errors();
            return Response::error(ERRORS::CLIENT_VALIDATION, $errors->all(':message')[0]);
        }
        $user = Auth::user();
        if ($user->hasVerifiedEmail()) {
            return Response::error(4101);
        }
        $pin = PIN::Check($user->email) ? $pin->delete() : '';

        PIN::Generate($request->email, VerifyEmail::class);
    
        return Response::success();
    }
}