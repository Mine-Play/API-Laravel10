<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Session;
use App\Models\Pin\Email as PIN;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Stevebauman\Location\Facades\Location;

use App\Helpers\Lang;
use App\Helpers\Email;
use App\Helpers\Response;

use App\Enums\Errors;

use App\Mail\VerifyEmail;

use Carbon\Carbon;


class LoginController extends Controller
{
    /**
    * Auth controller
    */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
    * User actions
    * Login, Logout, Register, RestorePass
    */
    public function login(Request $request)
    {
        $user = User\Instance::where('name', $request->name)->first();
        if (!$user) {
            $user = User\Instance::where('email', $request->name)->first();
            if(!$user){
                return Response::error(Errors::CLIENT_CREDENTIALS, Lang::get('login.errors.credentials'));
            }
        }


        if(!Hash::check($request->password, $user->password)){
            return Response::error(Errors::CLIENT_CREDENTIALS, Lang::get('login.errors.credentials'));
        }


        /**
        * START
        * If user's email is not confirmed
        * START
        */
        if($user->email_verified_at == NULL){
            $code = "old";
            $select = PIN::Check($user->email);
            if($select == NULL){
                $code = "new";
                PIN::Generate($user->email, VerifyEmail::class);
            }else{
                if(!Carbon::parse($select->created_at)->addHour()->gte(Carbon::now()->toDateTimeString())){
                    $code = "new";
                    PIN::Generate($user->email, VerifyEmail::class);
                }   
            }
            $email = [
                "status" => false,
                "code" => $code,
                "obusficated" => Email::obusficate($user->email)
            ];
        }else{
            $email = true;
        }
        /**
        * END
        * If user's email is not confirmed
        * END
        */
        return $this->respondWithToken($user->createToken("access_token")->plainTextToken, $user, $email);
    }
    protected function respondWithToken($token, $user, $email = true)
    {
        $agent = new \Jenssegers\Agent\Agent;
        if(\Request::ip() == '127.0 .0 .1' || \Request::ip() == 'localhost' || \Request::ip() == '127.0.0.1'){
            $location = Location::get('88.201.206.74');
        }else{
            $location = Location::get(\Request::ip());
        }
        $session = Session::create([
            "user_id" => $user->id,
            "token_id" => explode("|", $token)[0],
            "place" => 'Site',
            'device' => $agent->platform(),
            'attributes' => [
                "browser" => $agent->browser(),
                'country' => $location->countryName,
                'city' => $location->cityName
            ]
        ]);

        return Response::data([
            'access_token' => $token,
            'token_type' => 'bearer',
            'session_id' => $session->id,
            'email' => $email
        ], Lang::get('login.messages.successful', ["nickname" => $user->name]));
    }
}