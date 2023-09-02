<?php
 
namespace App\Http\Controllers;

use App\Helpers\Lang;

use App\Http\Controllers\Controller;

use App\Events\Users\UserRegistered;
use App\Events\Users\UserDeleted;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;


use App\Models\Wallet;
use App\Models\Role;
use App\Models\User;
 
class UserController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function me()
    {
        $user = Auth::user();
         $wallet = Wallet\Instance::me();
         $role = Role::me();
         $user->wallet = [
            "money" => $wallet->money,
            "keys" => $wallet->keys,
            "coins" => $wallet->coins,
         ];
         $user->role = [
            "id" => $user->role,
            "title" => $role->title,
            "color" => $role->color
         ];
         $user->skin = $user->skin();
        return response()->json(['response' => 200, 'data' => $user, 'time' => date('H:i', time()) ]);
    }
    public function changePassword(Request $request){
        $data = $request->all();
        $validator = Validator::make($data, [
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
 
            return \Response::json([
                'response' => 400,
                'error' => $errors->all(':message')[0],
                'time' => date('H:i', time()) 
            ]);
         }
         $user = Auth::user();
         if(!Hash::check($data["password"], User::where('name', $user->name)->first()->password)){
            return \Response::json([
                'response' => 400,
                'error' => Lang::get('auth.errors.password'),
                'time' => date('H:i', time()) 
            ]);
         }
         $user->changePassword($data["new_password"]);
         foreach($user->Sessions as $session){
            $session->kill();
         }
         Redis::publish('api_user_kick', json_encode([
            'uniqueId' => $user->id,
            'reason_code' => 1002,
            'params' => [
                'reason' => "На сайте был сменен ваш пароль. В целях безопасности выйдите из игры, и заново войдите в лаунчер."
            ]
         ]));
         return \Response::json([
            'response' => 200,
            'error' => Lang::get('api.users.successpass'),
            'time' => date('H:i', time()) 
        ]);
    }

    public function changeEmail(Request $request){
        $data = $request->all();
        $validator = Validator::make($data, [
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
 
            return \Response::json([
                'response' => 400,
                'error' => $errors->all(':message')[0],
                'time' => date('H:i', time()) 
            ]);
         }
         $user = Auth::user();
         if(!Hash::check($data["password"], User::where('name', $user->name)->first()->password)){
            return \Response::json([
                'response' => 400,
                'error' => Lang::get('auth.errors.password'),
                'time' => date('H:i', time()) 
            ]);
         }
         $user->changeEmail($data["new_email"]);
         return \Response::json([
            'response' => 200,
            'error' => Lang::get('api.users.successemail'),
            'time' => date('H:i', time()) 
        ]);
    }

    public function sessions(){
        $user = Auth::user();
        return response()->json(['response' => 200, 'data' => $user->Sessions, 'time' => date('H:i', time()) ]);
    }

    public function endSession(){
        $user = Auth::user();
        foreach ($user->Sessions as $session){
            $session->kill();
        }
        return response()->json(['response' => 200, 'message' => "Сессии были завершены.", 'time' => date('H:i', time()) ]);
    }

    public function getByUUID($uuid){
        if(User::getByUUID($uuid) == NULL){
            return response()->json(["response" => 404, "error" => Lang::get('api.users.notfound')], 404);
        }
        return response()->json(['response' => 200, 'data' => User::getByUUID($uuid), 'time' => date('H:i', time()) ]);
    }
    public function getByID($id){
        if(User::getByID($id) == NULL){
            return response()->json(["response" => 404, "error" => Lang::get('api.users.notfound')], 404);
        }
        return response()->json(['response' => 200, 'data' => User::getByID($id), 'time' => date('H:i', time()) ]);
    }
    public function getByLogin($login){
        if(User::getByLogin($login) == NULL){
            return response()->json(["response" => 404, "error" => Lang::get('api.users.notfound')], 404);
        }
        return response()->json(['response' => 200, 'data' => User::getByLogin($login), 'time' => date('H:i', time())]);
    }
}