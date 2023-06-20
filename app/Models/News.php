<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasUuids;
    
    public $timestamps = false;
    protected $table = 'news';
    protected $connection = 'Site';

    protected $fillable = [
        'title',
        'subtitle',
        'content',
        'time',
        'author'
    ];

    protected $attributes = [
        'views' => 0,
        'likes' => 0,
        'dislikes' => 0
    ];

    protected $casts = [
        'date'  => 'datetime:Y-m-d H:00'
    ];
    public static function getAll(){
        return News::all();
    }
    public static function getByID($id){
        return News::where('id', $id)->first();
    }
}
