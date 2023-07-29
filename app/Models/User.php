<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;

use App\Events\Users\UserRegistered;

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

    protected $dispatchesEvents = [
        'created' => UserRegistered::class,
        // 'deleted' => UserDeleted::class,
    ];

    
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
