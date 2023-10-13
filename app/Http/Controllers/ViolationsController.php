<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;


class ViolationsController extends Controller
{
    public function me(Request $request){
        $user = Auth::user();
        switch($request->type){
            case "ban":
                $violations = $user->Violations->where("type", "ban");
                break;
            default:
                $violations = $user->Violations;
                break;
        }
        return response()->json(['response' => 200, 'data' => $violations, 'time' => date('H:i', time()) ]);
    }
}