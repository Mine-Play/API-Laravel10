<?php
 
namespace App\Http\Controllers;

use App\Helpers\Lang;

use App\Http\Controllers\Controller;

use App\Events\Users\UserRegistered;
use App\Events\Users\UserDeleted;

use Illuminate\Support\Facades\Auth;


use App\Models\Wallet;
use App\Models\Role;
 
class UserController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function me()
    {
        $user = Auth::user();
         $wallet = Wallet::me();
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