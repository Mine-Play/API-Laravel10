<?php

namespace App\Models\New;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasUuids;
    
    public $timestamps = false;
    protected $table = 'News';
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
        return Item::all();
    }
    public static function getByID($id){
        return Item::where('id', $id)->first();
    }
}
