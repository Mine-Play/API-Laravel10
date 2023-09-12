<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;

use Illuminate\Foundation\Auth\RegistersUsers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;

use Illuminate\Http\Request;

use App\Mail\VerifyEmail;

use App\Helpers\Lang;


use App\Models\User;
use App\Models\Role;
use App\Models\VipReferal;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function register(Request $request){
         /**
          * Checking data with Laravel Validator
          * Translate
          */
        if ($this->validator($request->all())->fails()){
            $errors = $this->validator($request->all())->errors();
 
            return \Response::json([
                'response' => 400,
                'error' => $errors->all(':message')[0],
                'time' => date('H:i', time()) 
            ]);
         }
         /**
          * Password custom validation
          */
         if (preg_match('/\p{Cyrillic}/u', $request->get('password'))){
            return \Response::json([
                'response' => 400,
                'message' => Lang::get("register.errors.password.regexp"),
                'time' => date('H:i', time()) 
            ]);
        }
         /**
          * Register user
          */
        $user = $this->create($request);
         /**
          * Generate token
          */
        $token = $user->createToken('access_token')->plainTextToken;
        return $this->respondWithToken($token, $request->all()["name"]);
    }
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', "min:3", 'max:18', 'unique:Users', 'regex:/^[a-zA-Z0-9_]+$/'],
            'email' => ['required', 'string', 'email', 'max:50', 'unique:Users'],
            'password' => ['required', 'string', 'min:8', 'max:40']
        ], [
            /**
             * Name
             */
            'name.unique' => Lang::get("register.errors.name.unique", ["nickname" => $data["name"]]),
            'name.min' => Lang::get("register.errors.name.min"),
            'name.max' => Lang::get("register.errors.name.max"),
            "name.regex" => Lang::get("register.errors.name.regexp"),
            /**
             * Email
             */
            'email.unique' => Lang::get("register.errors.email.unique"),
            'email.max' => Lang::get("register.errors.email.max"),
            /**
             * Password
             */
            'password.min' => Lang::get("register.errors.password.min"),
            'password.max' => Lang::get("register.errors.password.max"),
            /**
             * Other
             */
            'required' => __('register')["errors"]['required']
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(Request $request)
    {
        $data = $request->all();
        if(!$referal = VipReferal::where('referal', $data["referal"])->select('id')->first()){
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => Role::default()->id,
                'referal' => null
            ]);
            $pin = rand(10000, 99999);
            DB::connection('Site')->table('Email_confirmations')
            ->insert(
                [
                    'email' => $request->all()['email'], 
                    'pin' => $pin
                ]
            );
            Mail::to($data['email'])->send(new VerifyEmail($pin));
            return $user;
        }
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => Role::default()->id,
            'referal' => $referal->id
        ]);
        $pin = rand(10000, 99999);
        DB::connection('Site')->table('Email_confirmations')
        ->insert(
            [
                'email' => $request->all()['email'], 
                'pin' => $pin
            ]
        );
        Mail::to($data['email'])->send(new VerifyEmail($pin));
        return $user;
    }
    protected function respondWithToken($token, $login)
    {
        return response()->json([
            'response' => 200,
            'message' => Lang::get("register.messages.successful", ["nickname" => $login]),
            'access_token' => $token,
            'token_type' => 'bearer',
            'time' => date('H:i', time()) 
        ]);
    }
}
