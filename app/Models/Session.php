<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasUuids;
    protected $table = 'Sessions';
    protected $connection = 'Global';

    protected $casts = [
        'attributes' => 'array',
        'last_use' => 'datetime:Y-m-d H:m:s'
    ];
    protected $fillable = [
        'token_id',
        'place',
        'device',
        'user_id'
    ];

    public function Token()
    {
        return $this->hasOne(Sanctum\PersonalAccessToken::class, 'token_id', 'tokenable_id');
    }
    public $timestamps = false;
}