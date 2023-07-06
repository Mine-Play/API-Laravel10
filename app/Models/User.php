<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail

{
    use Notifiable, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $attributes = [
        'last_login' => null,
        'level' => 1,
        'role' => 1,
        'status' => 'online'
    ];
    protected $fillable = [
        'name',
        'email',
        'ip',
        'password',
        'wid'
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
    /**
     * The attributes that shoнuld be cast.
     *
     * @var array<string, string>
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    public static function getByLogin($login){
        return User::where('name', $login)->select('id', 'name', 'created_at', 'last_login', 'level', 'role', 'status')->first();
    }
    public static function getByID($id){
        return User::where('id', $id)->select('id', 'name', 'created_at', 'last_login', 'level', 'role', 'status')->first();
    }
    protected $table = 'Users';
}
