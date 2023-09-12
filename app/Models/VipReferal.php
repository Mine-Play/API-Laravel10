<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class VipReferal extends Model
{
    use HasUuids;
    protected $fillable = [
        'author_id',
        'referal'
    ];
    protected $hidden = [
        'percents'
    ];
    public $timestamps = false;
    public function Author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
    protected $table = 'Vip_referals';
}
