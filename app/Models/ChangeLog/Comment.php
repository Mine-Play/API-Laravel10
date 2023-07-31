<?php

namespace App\Models\ChangeLog;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasUuids;
    protected $table = 'ChangeLogs_Comments';
    protected $connection = 'Site';

    protected $fillable = [
        'title',
        'author',
        'content',
        'changelog_id'
    ];
    protected $attributes = [
        'likes' => 0
    ];
    protected $casts = [
        'created_at'  => 'datetime:Y-m-d H:00'
    ];

    public function Item()
    {
        return $this->belongsTo(ChangeLog\Item::class, 'changelog_id');
    }
    public static function Write($changelog_id, $title, $content, $author){
       $comment = Comment::create([
        'changelog_id' => $changelog_id,
        'title' => $title,
        'content' => $content,
        'author' => $author
       ]);
       $comment->Item->comments++;
       $comment->Item->save();
    }
}
