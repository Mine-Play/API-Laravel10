<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Session;

use App\Http\Controllers\Controller;
use Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Stevebauman\Location\Facades\Location;

use App\Helpers\Lang;
use App\Helpers\Email;

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
    public function login()
    {
        $credentials = request(['name', 'password']);
        $user = User::where('name', $credentials["name"])->first();
        //return var_dump($user->tokens()->get()->pluck('token'));
        if (!$user) {
            $user = User::where('email', $credentials["name"])->first();
            if(!$user){
                return response()->json([
                    'response' => 401,
                    'error' => Lang::get('login.errors.credentials'),
                    'time' => date('H:i', time()) 
                ]);
            }
        }
        if(!Hash::check($credentials["password"], $user->password)){
            return response()->json([
                'response' => 401,
                'error' => Lang::get('login.errors.credentials'),
                'time' => date('H:i', time()) 
            ]);
        }
        $email = true;
        if($user->email_verified_at == NULL){
            $code = "old";
            $select = DB::connection('Site')->table('Email_confirmations')
            ->where('email', $user->email);
            if($select->first() == NULL){
                $code = "new";
                $pin = random_int(10000, 99999);
                DB::connection('Site')->table('Email_confirmations')->insert([
                    'email' => $user->email,
                    'pin' =>  $pin,
                    'created_at' => Carbon::now()->toDateTimeString()
                ]);
                Mail::to($user->email)->send(new VerifyEmail($pin));
            }else{
                if(!Carbon::parse($select->first()->created_at)->addHour()->gte(Carbon::now()->toDateTimeString())){
                    $code = "new";
                    $pin = random_int(10000, 99999);
                    DB::connection('Site')->table('Email_confirmations')->where('email', $user->email)->update([
                        'pin' =>  $pin,
                        'created_at' => Carbon::now()->toDateTimeString()
                    ]);
                    Mail::to($user->email)->send(new VerifyEmail($pin));
                }   
            }
            $email = [
                "status" => false,
                "code" => $code,
                "obusficated" => Email::obusficate($user->email)
            ];
        }
        return $this->respondWithToken($user->createToken("access_token")->plainTextToken, $user, $email);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
    protected function respondWithToken($token, $user, $email = true)
    {
        $agent = new \Jenssegers\Agent\Agent;
        if(Request::ip() == '127.0 .0 .1' || Request::ip() == 'localhost' || Request::ip() == '127.0.0.1'){
            $location = Location::get('88.201.206.74');
        }else{
            $location = Location::get(Request::ip());
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
        return response()->json([
            'response' => 200,
            'message' => Lang::get('login.messages.successful', ["nickname" => $user->name]),
                'access_token' => $token,
                'token_type' => 'bearer',
                'session_id' => $session->id,
                'email' => $email,
                'time' => date('H:i', time()) 
        ]);
    }
}