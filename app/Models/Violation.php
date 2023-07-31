<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Violation extends Model
{
    use HasUuids;
    protected $attributes = [
        'message' => null
    ];
    protected $fillable = [
        'type',
        'user',
        'moderator',
        'ending_at',
        'rules'
    ];
    protected $casts = [
        'created_at'  => 'datetime:Y-m-d H:m:s'
    ];
    public $timestamps = false;
    public function User()
    {
        return $this->belongsTo(User::class, 'user');
    }
}
