<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;

use App\Events\Users\UserRegistered;

use App\Models\Violation;

class User extends Authenticatable implements MustVerifyEmail

{
    use HasApiTokens, Notifiable, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $attributes = [
        'last_login' => null,
        // 'level' => 1,
        'role' => 1,
        'status' => 'online'
    ];
    protected $fillable = [
        'name',
        'email',
        // 'ip',
        'password'
    ];
    protected $hidden = [
        'password', 'remember_token'
    ];
    protected $casts = [
        'created_at'  => 'datetime:Y-m-d H:00',
        'email_verified_at' => 'datetime'
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

    public function addMoney($val, $dark = false, $history = true) {
        $this->Wallet->add("money", $val, $history);
    }
    public function addKeys($val, $history = true) {
        $this->Wallet->add("key", $val, $history);
    }
    public function addCoins($val, $history = true) {
        $this->Wallet->add("coin", $val, $history);
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
    
    /**
     * User model methods
     */
    public static function getByLogin($login){
        return User::where('name', $login)->select('id', 'name', 'created_at', 'last_login', 'level', 'role', 'status')->first();
    }
    public static function getByID($id){
        return User::where('id', $id)->select('id', 'name', 'created_at', 'last_login', 'level', 'role', 'status')->first();
    }
    protected $table = 'Users';
}
