<?php

namespace App\Models\New;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasUuids;
    public $timestamps = false;
    protected $table = 'News_categories';
    protected $connection = 'Site';

    protected $fillable = [
        'name',
        'description'
    ];
}
