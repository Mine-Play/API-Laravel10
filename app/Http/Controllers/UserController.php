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
         if(Hash::check($data["new_password"], User::where('name', $user->name)->first()->password)){
            return \Response::json([
                'response' => 400,
                'error' => Lang::get('auth.errors.password'),
                'time' => date('H:i', time()) 
            ]);
         }
         $user->changePassword($data["new_password"]);
         return \Response::json([
            'response' => 200,
            'error' => Lang::get('api.users.successpass'),
            'time' => date('H:i', time()) 
        ]);
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