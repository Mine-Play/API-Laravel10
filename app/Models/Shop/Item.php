<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasUuids;
    protected $fillable = [
        'title', 'description', 'vault', 'cost', 'category', 'type'
    ];
    protected $attributes = [
        'purchases' => 0
    ];
    public $timestamps = false;

    public function buy($user_id){
        
    }
}
