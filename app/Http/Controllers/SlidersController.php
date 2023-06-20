<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slider;

class SlidersController extends Controller
{
    public function getAll($type)
    {
        return response()->json(['response' => 200, 'data' => Slider::all()->where('type', $type), 'time' => date('H:i', time()) ]);
    }
}
