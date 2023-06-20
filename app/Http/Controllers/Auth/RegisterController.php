<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;

use Illuminate\Foundation\Auth\RegistersUsers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use Illuminate\Http\Request;

use App\Helpers\Lang;

use App\Models\User;
use App\Models\Wallet;

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
          * Authorize it
          */
        $token = auth()->login($user);
        return $this->respondWithToken($token, $request->all()["name"]);
    }
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', "min:3", 'max:18', 'unique:users', 'regex:/^[a-zA-Z0-9_]+$/'],
            'email' => ['required', 'string', 'email', 'max:50', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'max:40'],
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
        $wallet = Wallet::create();
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'ip' => $request->ip(),
            'wid' => $wallet->id
        ]);
        event(new Registered($user));
        return $user;
    }
    protected function respondWithToken($token, $login)
    {
        return response()->json([
            'response' => 200,
            'message' => Lang::get("register.messages.successful", ["nickname" => $login]),
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60,
                'time' => date('H:i', time()) 
        ]);
    }
}
