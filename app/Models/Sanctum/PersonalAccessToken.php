<?php
namespace App\Models\Sanctum;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;
 
class PersonalAccessToken extends SanctumPersonalAccessToken
{
    protected $casts = [
        'abilities' => 'json',
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'token',
        'abilities',
        'expires_at',
        'tokenable_type',
        'tokenable_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'token',
    ];

    public function Session()
    {
        return $this->belongsTo(Session::class, 'token_id', 'tokenable_id');
    }
}