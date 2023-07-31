<?php

namespace App\Models\Pass;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasUuids;
    protected $table = 'PlayPass_list';
    protected $connection = 'Global';

    protected $fillable = [
        'name',
        'season',
        'things_standart',
        'things_gold'
    ];
    protected $casts = [
        'beginning_at'  => 'datetime:Y-m-d H:m:s',
        'ending_at'  => 'datetime:Y-m-d H:m:s'
    ];
}
