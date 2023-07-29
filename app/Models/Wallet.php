<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Auth;

class Wallet extends Model
{
    
    public $timestamps = false;
    protected $table = 'Wallets';
    protected $connection = 'Global';

    protected $fillable = [
        'id'
    ];

    protected $attributes = [
        'money' => 0,
        'coins' => 0,
        'keys' => 0,
        'history' => NULL
    ];
    public static function me(){
        $user = Auth::user();
        return Wallet::where('id', $user->id)->select('id', 'money', 'coins', 'keys', 'history')->first();
    }
}
