<?php
 
namespace App\Http\Controllers\User;

use App\Helpers\Lang;
use App\Helpers\Response;

use App\Enums\Errors;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use App\Models\Pin\Email as PIN;
use App\Mail\VerifyEmail;
 
class ChangeController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function Password(Request $request){
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:8', 'max:40'],
            'new_password' => ['required', 'string', 'min:8', 'max:40'],
        ], [
            /**
             * Password
             */
            'password.min' => Lang::get("register.errors.password.min"),
            'password.max' => Lang::get("register.errors.password.max"),
            /**
             * Password
             */
            'new_password.min' => Lang::get("register.errors.password.min"),
            'new_password.max' => Lang::get("register.errors.password.max"),
            /**
             * Other
             */
            'required' => __('register')["errors"]['required']
        ]);
        if ($validator->fails()){
            $errors = $validator->errors();
            return Response::error(ERRORS::CLIENT_VALIDATION, $errors->all(':message')[0]);
         }
         $user = Auth::user();
         if(!Hash::check($request->password, $user->password)){
            return Response::error(ERRORS::CLIENT_CREDENTIALS, Lang::get('auth.errors.password'));
         }
         $user->changePassword($request->new_password);
         foreach($user->Sessions as $session){
            $session->kill();
         }
         return Response::success();
    }

    public function Email(Request $request){
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:8', 'max:40'],
            'new_email' => ['required', 'string', 'email', 'max:50', 'unique:Users'],
        ], [
            /**
             * Password
             */
            'password.min' => Lang::get("register.errors.password.min"),
            'password.max' => Lang::get("register.errors.password.max"),
            /**
             * Email
             */
            'email.unique' => Lang::get("register.errors.email.unique"),
            'email.max' => Lang::get("register.errors.email.max"),
            /**
             * Other
             */
            'required' => __('register')["errors"]['required']
        ]);
        if ($validator->fails()){
            $errors = $validator->errors();
            return Response::error(ERRORS::CLIENT_VALIDATION, $errors->all(':message')[0]);
        }
        $user = Auth::user();
        if(!Hash::check($request->password, $user->password)){
           return Response::error(ERRORS::CLIENT_CREDENTIALS, Lang::get('auth.errors.password'));
        }
        if(PIN::check($request->email) == NULL)
        PIN::check($request->email) ? $type = "old" : PIN::Generate($request->email, VerifyEmail::class); $type = "new";
        //  $user->changeEmail($data["new_email"]);
        return Response::data(["type" => $type]);
    }
}