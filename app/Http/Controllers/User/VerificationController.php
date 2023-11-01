<?php
 
namespace App\Http\Controllers\User;

use App\Helpers\Lang;
use App\Helpers\Response;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Models\Pin\Email as PIN;

use App\Mail\VerifyEmail;
 
class VerificationController extends Controller
{
    public function Email(Request $request){
        $validator = Validator::make($request->all(), [
            'pin' => ['required', 'integer', 'min:10000', 'max:99999'],
        ]);
    
        if ($validator->fails()) {
            $errors = $validator->errors();
            return Response::error(ERRORS::CLIENT_VALIDATION, $errors->all(':message')[0]);
        }
         $user = Auth::user();
         if(!Hash::check($data["password"], $user->password)){
            return \Response::json([
                'response' => 400,
                'error' => Lang::get('auth.errors.password'),
                'time' => date('H:i', time()) 
            ]);
         }
         $pin = rand(10000, 99999);
         DB::connection('Site')->table('Email_confirmations')
         ->insert(
             [
                 'email' => $request->all()['email'], 
                 'pin' => $pin
             ]
         );
         Mail::to($data['email'])->send(new VerifyEmail($pin));
        //  $user->changeEmail($data["new_email"]);
         return \Response::json([
            'response' => 200,
            'time' => date('H:i', time()) 
        ]);
    }
}