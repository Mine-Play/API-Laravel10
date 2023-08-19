<?php

namespace App\Models\Sanctum;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;
 
class PersonalAccessToken extends SanctumPersonalAccessToken
{
    use HasUuids;

    protected $fillable = [
        'name',
        'tokenable_type',
        'tokenable_id'
    ];
    protected $hidden = [
        'token'
    ];
    protected $attributes = [
        'token' => null
    ];
    protected $casts = [
        'last_used_at'  => 'datetime:Y-m-d H:m:s',
        'expires_at'  => 'datetime:Y-m-d H:m:s',
        'created_at'  => 'datetime:Y-m-d H:m:s',
        'updated_at'  => 'datetime:Y-m-d H:m:s'
    ];
}