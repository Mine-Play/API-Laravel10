<?php

namespace App\Models\ChangeLog;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasUuids;
    protected $table = 'ChangeLogs';
    protected $connection = 'Global';

    protected $fillable = [
        'title',
        'author',
        'content',
        'comment'
    ];
    protected $attributes = [
        'attrs' => null,
        'likes' => 0,
        'comments' => 0
    ];
    protected $casts = [
        'created_at'  => 'datetime:Y-m-d H:00'
    ];
}
