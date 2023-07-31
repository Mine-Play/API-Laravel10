<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;


class ViolationsController extends Controller
{
    public function me(){
        $user = Auth::user();
        //var_dump($user);
        return response()->json(['response' => 200, 'data' => $user->Violations, 'time' => date('H:i', time()) ]);
    }
}
