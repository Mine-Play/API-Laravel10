<?php

namespace App\Http\Controllers\Launcher;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\User;
use App\Models\Session;

class AuthController extends Controller
{
    private $credentials;
    public function current(){
        if(!$user = Auth::user()){
            return response()->json([
                'error' => 'auth.invalidtoken',
                'time' => date('H:i', time()) 
            ], 500);
        }
        return response()->json($this->HttpUserSession($user->name, request()->bearerToken())); 
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
    public function joinServer() {
        $data = request(['username', 'accessToken', 'serverId']);
        $session = Session::where('token_id', explode("|", $data["accessToken"])[0])->first();
        $session->attributes = json_encode(["serverId" => $data["serverId"]]);
        $session->save();
        return response()->json([
            'response' => 200,
            'time' => date('H:i', time()) 
        ]);
    }
    public function checkServer() {
        $data = request(['username', 'serverId']);
        $user = User::where("name", $data["username"])->select("id")->first();
        $serverId = Session::where("user_id", $user->id)->select("attributes")->first()->attributes;
        if(json_decode($serverId)->serverId != $data["serverId"]){
            return response()->json([
                'error' => 'auth.invalidtoken',
                'time' => date('H:i', time()) 
            ], 500);
        }
        return $this->HttpUser($data["username"], "");
    }
    public function refresh(){
        $data = request(['context', 'refreshToken']);
        $token = $user->createToken("access_token")->plainTextToken;
        return response()->json([
            'minecraftAccessToken' => $token,
            'oauthAccessToken' => $token,
            'oauthRefreshToken' => $token,
            'oauthExpire' => 0,
            'session' => $this->HttpUserSession(),
            'time' => date('H:i', time()) 
        ]);
    }
    public function login()
    {
        $credentials = request(['login', 'context', 'password', 'minecraftAccess']);
        $this->credentials = $credentials;
        $user = User::where('name', $credentials["login"])->first();
        if (!$user) {
            $user = User::where('email', $credentials["login"])->first();
            if(!$user){
                return response()->json([
                    'error' => 'auth.wrongpassword',
                    'time' => date('H:i', time()) 
                ], 500);
            }
        }
        if(!Hash::check($credentials["password"]["password"], $user->password)){
            return response()->json([
                'error' => 'auth.wrongpassword',
                'time' => date('H:i', time()) 
            ], 500);
        }
        $token = $user->createToken("access_token")->plainTextToken;
        if(!$credentials["minecraftAccess"]){
            return response()->json([
                'oauthAccessToken' => $token,
                'oauthRefreshToken' => $token,
                'oauthExpire' => 0,
                'session' => $this->HttpUserSession($credentials["login"], $token),
                'time' => date('H:i', time()) 
            ]);
        }
        return response()->json([
            'minecraftAccessToken' => $token,
            'oauthAccessToken' => $token,
            'oauthRefreshToken' => $token,
            'oauthExpire' => 0,
            'session' => $this->HttpUserSession($credentials["login"], $token),
            'time' => date('H:i', time()) 
        ]);
    }
    private function HttpUserSession($login, $token) {
        if(!$session = Session::where('token_id', explode('|', $token)[0])->select('id')->first()){
            $session = Session::create([
                "user_id" => User::where("name", $login)->select("id")->first()->id,
                "token_id" => explode("|", $token)[0],
                "place" => 'Launcher',
                'device' => 'Desktop'
            ]);
        }
        return [
            'id' => $session->id,
            'user' => $this->HttpUser($login, $token),
            'expireIn' => 0,
        ];
    }
    private function HttpUser($login, $token){
        $user = User::where('name', $login)->first();
        if (!$user) {
            $user = User::where('email', $login)->first();
        }
        $role = Role::getByID($user->role);
        return [
            'username' => $user->name,
            'uuid' => $user->id,
            'accessToken' => $token,
            'permissions' => [
                "perms" => [],
                'roles' => [ $role->title ],
            ],
            'assets' => [
                'SKIN' => [
                    'url' => "https://mpapi.uniondev.ru/launcher/skins/".$user->name.".png",
                    'digest' => '',
                    'metadata' => []
                ],
                'properties' => ['key' => 'value']
            ]
        ];
    }
}