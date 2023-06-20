<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Wallet extends Model
{
    use HasUuids;
    
    public $timestamps = false;
    protected $table = 'wallets';
    protected $connection = 'Global';

    protected $attributes = [
        'money' => 0,
        'coins' => 0,
        'keys' => 0,
        'history' => NULL
    ];
    public static function getMy($wid){
        return Wallet::where('id', $wid)->select('id', 'money', 'coins', 'keys', 'history')->first();
    }
    public static function wid($wid){
        return Wallet::where('id', $wid)->select('id', 'money', 'coins', 'keys')->first();
    }
}
