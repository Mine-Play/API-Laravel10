<?php

namespace App\Models\Wallet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasUuids;
    public $timestamps = false;
    protected $table = 'Wallet_history';
    protected $connection = 'Site';
    protected $fillable = [
        'wid',
        'amount',
        'type',
        'bill'
    ];

    protected $casts = [
        'date'  => 'datetime:Y-m-d H:m:s'
    ];
    protected $attributes = [
        "category" => null,
        "title" => null
    ];
    public function Wallet()
    {
        return $this->belongsTo(Instance::class, 'wid', 'id');
    }

    public function add($data){
        $history = History::create([
            'wid' => $data["wid"],
            'type' => $data["type"],
            'amount' => $data["amount"]
        ]);
        return $history->id;
    }
}
