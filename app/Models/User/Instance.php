<?php

namespace App\Models\User;

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
use App\Models\New;
use App\Models\Wallet;
use App\Models\Session;

use App\Helpers\Avatar;

use Carbon\Carbon;
use Carbon\CarbonInterface;

class Instance extends Authenticatable implements MustVerifyEmail, TwoFactorAuthenticatable

{
    use HasApiTokens, Notifiable, HasUuids, TwoFactorAuthentication;


    protected $attributes = ['last_login' => null, 'referal' => null, 'skin' => 0, 'cloak' => 0, 'avatar' => 0, 'banner' => 0, 'level' => 1, 'exp' => 0, 'likes' => null];

    protected $fillable = ['name', 'email', 'password', 'role', 'level', 'exp'];

    protected $hidden = ['password', 'params', 'password_reset', 'totp', 'referal', 'created_at', 'email_verified_at', 'email'];

    protected $casts = ['email_verified_at' => 'datetime:Y-m-d H:m:s', 'params' => 'array', 'likes' => 'array'];

    public $timestamps = false;

    /*
        Custom Events Manager
    */
    protected $dispatchesEvents = [
        'created' => UserRegistered::class
    ];

    /*
        User Relationships
    */
    public function Violations(){
        return $this->hasMany(Violation::class, 'user');
    }

    public function News(){
        return $this->hasMany(New\Item::class, 'author');
    }

    public function Wallet(){
        return $this->hasOne(Wallet\Instance::class, 'id', 'id');
    }

    public function Sessions(){
        return $this->hasMany(Session::class, 'user_id', 'id');
    }
    

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
        $this->password_reset = Carbon::now()->toDateTimeString();
        $this->password = Hash::make($newpass);
        $this->save();
    }

    public function changeEmail($newmail){
        $this->email = $newmail;
        $this->save();
    }
    public function daysAfterRegister(){
        $created_at = Carbon::parse($this->created_at);
        $diff = Carbon::now()->diffForHumans($created_at, ['syntax' => CarbonInterface::DIFF_ABSOLUTE]);
        return [
            "date" => $this->created_at,
            "human" => $diff
        ];
    }
    public function passwordStatus(){
        $reset_date = Carbon::parse($this->password_reset);
        $diff = Carbon::now()->diffForHumans($reset_date, ['syntax' => CarbonInterface::DIFF_ABSOLUTE]);
        $status = 0;
        if(Carbon::now()->diffInMonths($reset_date) >= 3){
            $status = 1;
        }
        return [
            "date" => $this->password_reset,
            "human" => $diff,
            "status" => $status
        ];
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
    public function unban($moderator, $reason) {
        $this->Violations->where("type", "ban")->first()->delete();
    }
    public function banner(){
        switch ($this->banner){
            case 0:
                return [ "path" => Storage::url(env('TEXTURES_DEFAULT_BANNER_URL')), "status" => 0 ];
                break;
        }
    }
    public function skin() {
        switch($this->skin){
            case 1:
                $path = Storage::url('/users/'.$this->id.'/skin.png?v='.Storage::lastModified('/users/'.$this->id.'/skin.png'));
                $type = $this->params["skin"]["type"];
                break;
            case 2:
                switch ($this->params["skin"]["name"]){
                    case "steve":
                        $path = Storage::url('/users/default/skins/steve.png');
                        break;
                    case "play":
                        $path = Storage::url('/users/default/skins/play.png');
                        break;
                }
                $type = $this->params["skin"]["type"];
                break;
            default:
                $path = Storage::url(env('TEXTURES_DEFAULT_SKIN_URL', '/users/default/skins/play.png'));
                $type = env('TEXTURES_DEFAULT_SKIN_TYPE', 'slim');
                break;
        }
        return [
            "path" => $path,
            "type" => $type
        ];
    }
    public function cloak() {
        switch($this->cloak){
            case 0:
                return [ "status" => 0 ];
                break;
            case 1:
                return [ "path" => Storage::url('/users/'.$this->id.'/cloak.png?v='.Storage::lastModified('/users/'.$this->id.'/cloak.png')), "status" => 1 ];
                break;
        }
    }
    public function setSkin($type, $skin = 1, $params = null){
        $this->skin = $skin;
        if($params == NULL) {
            $params = array("skin" => ["type" => $type]);
        }
        if($this->params == null){
            $this->params = $params;
        }else{
            $this->params = array_merge($this->params, $params);
        }
        $this->save();
    }
    public function setCloak(){
        $this->cloak = 1;
        $this->save();
    }
    public function avatar() {
        switch($this->avatar){
            case 0:
                return [
                    "path" => Avatar::classic($this->skin()["path"]),
                    "status" => 0
                ];
                break;
            default:
                return [
                    "path" => Avatar::classic($this->skin()["path"]),
                    "status" => 0
                ];
                break;
        }
    }
    protected $table = 'Users';
}
