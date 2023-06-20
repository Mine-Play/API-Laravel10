<?php
 
namespace App\Http\Controllers;

use App\Helpers\Lang;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Role;
 
class UserController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function me()
    {
         $info = Auth::user();
         $wid = Wallet::wid($info->wid);
         $role = Role::getByID($info->role);
         $info->wallet = [
            "id" => $info->wid,
            "money" => $wid->money,
            "keys" => $wid->keys,
            "coins" => $wid->coins,
         ];
         $info->role = [
            "id" => $info->role,
            "title" => $role->title,
            "color" => $role->color
         ];
        return response()->json(['response' => 200, 'data' => $info, 'time' => date('H:i', time()) ]);
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