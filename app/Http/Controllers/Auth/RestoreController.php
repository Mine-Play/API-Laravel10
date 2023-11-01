<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

use App\Mail\RestorePassword;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Pin\Password as PIN;

use App\Helpers\Response;

use App\Enums\Errors;


class RestoreController extends Controller
{
    public function send(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'max:500', 'exists:Users,email'],
        ]);
    
        if ($validator->fails()) {
            $errors = $validator->errors();
            return Response::error(ERRORS::CLIENT_VALIDATION, $errors->all(':message')[0]);
        }
        $select = PIN::select('created_at')->where('email', $user->email)->first();
        if($select == NULL){
            PIN::Generate($user->email, VerifyEmail::class);
            return Response::data(["code" => "new"]);
        }else{
            return Response::data(["code" => "old"]);  
        }
    }
    public function verify(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'max:500', 'exists:Users,email'],
            'pin' => ['required', 'integer', 'min:10000', 'max:99999'],
        ]);
    
        if ($validator->fails()) {
            $errors = $validator->errors();
            return Response::error(ERRORS::CLIENT_VALIDATION, $errors->all(':message')[0]);
        }
        return PIN::Check($request->email, $request->pin) ? Response::success() : Response::error(ERRORS::CLIENT_CREDENTIALS);
    }
    public function change(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'max:500', 'exists:Users,email'],
            'pin' => ['required', 'integer', 'min:10000', 'max:99999'],
            'password' => ['required', 'string', 'min:8', 'max:40']
        ]);
    
        if ($validator->fails()) {
            $errors = $validator->errors();
            return Response::error(ERRORS::CLIENT_VALIDATION, $errors->all(':message')[0]);
        }

        if(PIN::Check($request->email, $request->pin)){
            return Response::error(ERRORS::CLIENT_CREDENTIALS);
        }
        $user = User\Instance::where('email', $request->email)->first();
        $user->changePassword($request->password);
        foreach ($user->Sessions as $session){
            $session->kill();
        }
        PIN::Erase($request->email, $request->pin);
        return Response::success();
    }
}
