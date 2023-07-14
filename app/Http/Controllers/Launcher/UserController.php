<?php

namespace App\Http\Controllers\Launcher;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;

class UserController extends Controller
{
    /**
    * Auth controller
    */
    // public function __construct()
    // {
    //     $this->middleware('auth:api', ['except' => ['login']]);
    // }

    public function getByName($name){
        return response()->json($this->HttpUser(User::getByLogin($name)));
    }
    public function getByUuid($uuid){
        return response()->json($this->HttpUser(User::getById($uuid)));
    }
    private function HttpUser($user){
        $role = Role::getByID($user->role);
        return [
            'username' => $user->name,
            'uuid' => $user->id,
            'permissions' => [
                "perms" => [],
                'roles' => [ $role->title ],
            ],
            'assets' => [
                'SKIN' => [
                    'url' => "https://example.com/skins/".$user->name.".png",
                    'digest' => '',
                    'metadata' => []
                ],
                'properties' => ['key' => 'value']
            ]
        ];
    }
}