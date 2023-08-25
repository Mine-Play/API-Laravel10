<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Auth;

class Role extends Model
{
    use HasUuids;
    
    public $timestamps = false;
    protected $table = 'Roles';
    protected $connection = 'Global';

    protected $fillable = [
        'title', 'color', 'index', 'default', 'permissions'
    ];
    public static function getByID($id){
        return Role::where('id', $id)->select('title', 'color', 'index')->first();
    }
    public static function default(){
        return Role::where('default', 1)->select('id', 'title', 'color', 'index', 'default')->first();
    }
    public static function me(){
        $user = Auth::user();
        return Role::where('id', $user->role)->select('title', 'color', 'index')->first();
    }
}