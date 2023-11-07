<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Foundation\Auth\RegistersUsers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;

use Stevebauman\Location\Facades\Location;

use Illuminate\Http\Request;

use App\Mail\VerifyEmail;

use App\Helpers\Lang;
use App\Helpers\Response;

use App\Enums\Errors;

use App\Models\User;
use App\Models\Role;
use App\Models\Session;
use App\Models\Pin\Email as PIN;

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

    public function register(Request $request){
        if ($this->validator($request->all())->fails()){
            $errors = $this->validator($request->all())->errors();
            return Response::error(ERRORS::CLIENT_VALIDATION, $errors->all(':message')[0]);
        }
        if (preg_match('/\p{Cyrillic}/u', $request->get('password'))){
            return Response::error(ERRORS::CLIENT_VALIDATION, Lang::get("register.errors.password.regexp"));
        }


        $user = $this->create($request);
        $token = $user->createToken('access_token')->plainTextToken;
        return $this->respondWithToken($token, $user);
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
        $user = User\Instance::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => Role::default()->id,
            'referal' => null
        ]);
        PIN::generate($request->email, VerifyEmail::class);
        return $user;
    }
    protected function respondWithToken($token, $user)
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
