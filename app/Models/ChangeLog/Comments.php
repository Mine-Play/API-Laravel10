<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class CComments extends Model
{
    use HasUuids;
    protected $table = 'ChangeLogs_Comments';
    protected $connection = 'Site';

    protected $fillable = [
        'title',
        'author',
        'content'
    ];
    protected $attributes = [
        'likes' => 0
    ];
    protected $casts = [
        'created_at'  => 'datetime:Y-m-d H:00'
    ];
}
