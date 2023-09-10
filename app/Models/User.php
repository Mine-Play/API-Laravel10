<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

use Laragear\TwoFactor\TwoFactorAuthentication;
use Laragear\TwoFactor\Contracts\TwoFactorAuthenticatable;


use App\Events\Users\UserRegistered;

use App\Models\Violation;
use App\Models\Role;

class User extends Authenticatable implements MustVerifyEmail, TwoFactorAuthenticatable

{
    use HasApiTokens, Notifiable, HasUuids, TwoFactorAuthentication;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $attributes = [
        'last_login' => null,
        'referal' => null,
        'skin' => 0,
        'cloak' => 0,
        'avatar' => 0,
        'banner' => 0,
        'level' => 1,
        'exp' => 0
    ];
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'level',
        'exp'
    ];
    protected $hidden = [
        'password', 'remember_token', 'params'
    ];
    protected $casts = [
        'created_at'  => 'datetime:Y-m-d H:m:s',
        'email_verified_at' => 'datetime:Y-m-d H:m:s',
        'params' => 'array'
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    public $timestamps = false;
    public function ban($moderator, $ending_at, $rules, $message = null){
        $violation = Violation::create([
            "user" => $this->id,
            "type" => "ban",
            "ending_at" => $ending_at,
            "moderator" => $moderator,
            "rules" => $rules,
            "message" => $message
        ]);
        return $violation->id;
    }

    public function changePassword($newpass){
        $this->password = Hash::make($newpass);
        $this->save();
    }

    public function changeEmail($newmail){
        $this->email = $newmail;
        $this->save();
    }

    public function addMoney($val, $dark = false, $history = true) {
       return $this->Wallet->add("money", $val, $history);
    }
    public function addKeys($val, $history = true) {
        return $this->Wallet->add("key", $val, $history);
    }
    public function addCoins($val, $history = true) {
        return $this->Wallet->add("coin", $val, $history);
    }

    public function excahngeKeys($val) {
        $this->Wallet->exchange("keys", $val);
    }
    public function excahngeCoins($val) {
        $this->Wallet->exchange("coins", $val);
    }
    public function unban($moderator, $reason) {
        $this->Violations->where("type", "ban")->first()->delete();
    }

    public function skin() {
        switch($this->skin){
            case 1:
                $path = Storage::url('/users/'.$this->id.'/skins/skin.png?v='.Storage::lastModified('/users/'.$this->id.'/skins/skin.png'));
                $type = $this->params["skin"]["type"];
                break;
            default:
                $path = Storage::url(env('TEXTURES_DEFAULT_SKIN_URL', '/users/default/skin.png'));
                $type = env('TEXTURES_DEFAULT_SKIN_TYPE', 'default');
                break;
        }
        return [
            "path" => $path,
            "type" => $type
        ];
    }
    public function setSkin($type){
        $this->skin = 1;
        if($this->params == null){
            $this->params = array("skin" => ["type" => $type]);
        }else{
            $this->params = array_merge($this->params, array("skin" => ["type" => $type]));
        }
        $this->save();
    }
    public function cloak() {
        switch($this->cloak){
            case 1:
                $path = Storage::url('/users/'.$this->id.'cloaks/cloak.png');
                break;
            default:
                return null;
                break;
        }
        return [
            "path" => $path
        ];
    }
    public function avatar() {
        switch($this->avatar){
            case 1:
                $path = Storage::url('/users/'.$this->id.'skins/skin.png');
                $type = "INDEV";
                break;
            default:
                $path = Storage::url(env('TEXTURES_DEFAULT_SKIN_URL', '/users/default/skin.png'));
                $type = env('TEXTURES_DEFAULT_SKIN_TYPE', 'default');
                break;
        }
        return [
            "path" => $path,
            "type" => $type
        ];
    }
    protected $dispatchesEvents = [
        'created' => UserRegistered::class
    ];

    public function Violations()
    {
        return $this->hasMany(Violation::class, 'user');
    }

    public function Wallet()
    {
        return $this->hasOne(Wallet\Instance::class, 'id', 'id');
    }

    public function Sessions()
    {
        return $this->hasMany(Session::class, 'user_id', 'id');
    }
    
    /**
     * User model methods
     */
    public static function getByLogin($login){
        return User::where('name', $login)->select('id', 'name', 'created_at', 'last_login', 'role')->first();
    }
    public static function getByID($id){
        return User::where('id', $id)->select('id', 'name', 'created_at', 'last_login', 'role')->first();
    }
    protected $table = 'Users';
}
