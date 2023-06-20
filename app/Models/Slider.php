<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use Notifiable, HasUuids;
    protected $table = 'sliders';
    protected $connection = 'Site';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $casts = [
        'params' => 'array'
    ];
    protected $fillable = [
        'title',
        'subtitle',
        'type',
        'link'
    ];
}