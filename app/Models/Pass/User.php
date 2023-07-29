<?php

namespace App\Models\Pass;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'PlayPass_users';    
    protected $connection = 'Global';

    protected $fillable = [
        'id'
    ];
    protected $attributes = [
        'type' => "standart",
        'level' => 1,
        'tokens' => 0,
        'not_equiped_standart' => [1],
        'not_equiped_gold' => [],
    ];
}