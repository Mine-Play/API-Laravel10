<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

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
        'user_id',
        'attributes'
    ];

    public function Token()
    {
        return $this->hasOne(Sanctum\PersonalAccessToken::class, 'token_id', 'tokenable_id');
    }
    public function User()
    {
        return $this->belongsTo(User\Instance::class, 'user_id', 'id');
    }

    public function kill(){
        $this->User->tokens()->delete();
        $this->delete();
    }
    public $timestamps = false;
}