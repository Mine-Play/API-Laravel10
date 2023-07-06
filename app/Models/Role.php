<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasUuids;
    
    public $timestamps = false;
    protected $table = 'Roles';
    protected $connection = 'Global';

    protected $fillable = [
        'title',
        'color',
        'index',
        'permissions'
    ];
    public static function getByID($id){
        return Role::where('id', $id)->select('title', 'color', 'index')->first();
    }
}