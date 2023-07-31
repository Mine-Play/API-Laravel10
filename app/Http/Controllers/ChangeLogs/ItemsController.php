<?php

namespace App\Http\Controllers\ChangeLogs;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\Lang;

use App\Models\ChangeLog\Item;

class ItemsController extends Controller
{


    public function getAll()
    {
        return response()->json(['response' => 200, 'data' => Item::all(), 'time' => date('H:i', time()) ]);
    }
}