<?php

namespace App\Http\Controllers\Launcher;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Models\Role;

class AuthController extends Controller
{
    private $credentials;
    public function current(){
        return response()->json($this->HttpUser()); 
    }
    public function details() {
        return response()->json([
            "details" => [
                [
                    "type" => "password"
                ]
            ]
        ]);
    }
    public function updateServer() {
        return response()->json([
            'response' => 200,
            'time' => date('H:i', time()) 
        ]);
    }
    public function refresh(){
        $data = request(['context', 'refreshToken']);
        $token = auth()->refresh();
        return response()->json([
            'minecraftAccessToken' => $token,
            'oauthAccessToken' => $token,
            'oauthRefreshToken' => $token,
            'oauthExpire' => auth()->factory()->getTTL() * 60,
            'session' => $this->HttpUserSession(),
            'time' => date('H:i', time()) 
        ]);
    }
    public function login()
    {
        $credentials = request(['login', 'context', 'password', 'minecraftAccess']);
        $this->credentials = $credentials;
        if (! $token = auth()->attempt(["name" => $credentials["login"], "password" => $credentials["password"]["password"]])) {
            return response()->json([
                'error' => 'auth.wrongpassword',
                'time' => date('H:i', time()) 
            ]);
        }
        if(!$credentials["minecraftAccess"]){
            return response()->json([
                'oauthAccessToken' => $token,
                'oauthRefreshToken' => $token,
                'oauthExpire' => auth()->factory()->getTTL() * 60,
                'session' => $this->HttpUserSession(),
                'time' => date('H:i', time()) 
            ]);
        }
        return response()->json([
            'minecraftAccessToken' => $token,
            'oauthAccessToken' => $token,
            'oauthRefreshToken' => $token,
            'oauthExpire' => auth()->factory()->getTTL() * 60,
            'session' => $this->HttpUserSession(),
            'time' => date('H:i', time()) 
        ]);
    }
    private function HttpUserSession() {
        return [
            'id' => Str::uuid(),
            'user' => $this->HttpUser(),
            'expireIn' => auth()->factory()->getTTL() * 60,
        ];
    }
    private function HttpUser(){
        $user = Auth::user();
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
