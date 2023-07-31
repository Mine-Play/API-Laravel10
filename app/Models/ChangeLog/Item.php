<?php

namespace App\Models\ChangeLog;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

//use App\Models\ChangeLog\Comment;

class Item extends Model
{
    use HasUuids;
    protected $table = 'ChangeLogs';
    protected $connection = 'Global';

    protected $fillable = [
        'title',
        'author',
        'content',
        'description',
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
    public $timestamps = false;
    public function comments()
    {
        return $this->hasMany(ChangeLog\Comment::class, 'changelog_id');
    }

    // protected $dispatchesEvents = [
    //     'created' => ChangeLogCreated::class
    // ];
}
