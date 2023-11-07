<?php
 
namespace App\Http\Controllers\User;

use App\Helpers\Lang;
use App\Helpers\Response;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Models\Wallet;
use App\Models\Role;
use App\Models\User;

use App\Enums\Errors;

use StdClass;
 
class MainController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     * Get Authorized User Information
     */
    public function me()
    {
        $user = Auth::user();
        $wallet = Wallet\Instance::me();
        $role = Role::me();
        $response = new StdClass;
        $response->id = $user->id;
        $response->name = $user->name;
        $response->last_login = $user->last_login;
        $response->exp = $user->exp;
        $response->likes = $user->likes;
        $response->wallet = [
           "money" => $wallet->money,
           "keys" => $wallet->keys,
           "coins" => $wallet->coins,
        ];
        $response->role = [
           "id" => $user->role,
           "title" => $role->title,
           "color" => $role->color
        ];
        $response->skin = $user->skin();
        $response->cloak = $user->cloak();
        $response->avatar = $user->avatar();
        $response->banner = $user->banner();
        $response->registered_at = $user->daysAfterRegister();
        $response->password_status = $user->passwordStatus();
        return Response::data($response);
    }
    public function getByID($alias, $id){
        $user = User\Instance::select('id', 'name', 'role', 'last_login', 'skin', 'cloak', 'avatar', 'banner', 'level')->find($id);
        return $user ? Response::data($user) : Response::error(Errors::CLIENT_NOT_FOUND);
    }

    public function getByName($alias, $name){
        $user = User\Instance::select('id', 'name', 'role', 'last_login', 'skin', 'cloak', 'avatar', 'banner', 'level')->where('name', $name)->first();
        return $user ? Response::data($user) : Response::error(Errors::CLIENT_NOT_FOUND);
    }
}