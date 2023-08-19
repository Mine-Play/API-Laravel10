<?php

namespace App\Models\Wallet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Auth;

use App\Models\User;

class Instance extends Model
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
        'keys' => 0
    ];
    public static function me(){
        $user = Auth::user();
        return Instance::where('id', $user->id)->select('id', 'money', 'coins', 'keys')->first();
    }

    public function add($wallet, $val, $history) {
        switch ($wallet) {
            case "money":
                $this->money += $val;
                break;
            case "key":
                $this->keys += $val;
                break;
            case "coin":
                $this->coins += $val;
                 break;
        }
        $this->save();
        if($history){
            $hist = History::create([
                "wid" => $this->User->id,
                "type" => "add",
                "bill" => $wallet,
                "amount" => $val
            ]);
            return $hist->id;
        }
    }
    public function User()
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }
    public function History() {
        return $this->hasMany(History::class, 'wid', 'id');
    }
}
