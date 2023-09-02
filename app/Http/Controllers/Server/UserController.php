<?php

namespace App\Http\Controllers\Server;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\Session;

class UserController extends Controller
{
    public function init($uuid){
        $wallet = Wallet\Instance::where('id', $uuid)->select('id', 'money', 'coins', 'keys')->first();
        if($wallet == NULL){
            return \Response::json([
                'response' => 1001,
                'time' => date('H:i', time()) 
            ]);   
        }
        return \Response::json([
            'response' => 200,
            'data' => [
                "uniqueId" => $uuid,
                "balance" => [
                    "money" => $wallet->money,
                    "coins" => $wallet->coins
                ],
                "level" => [
                    "level" => $wallet->User->level,
                    "exp" => $wallet->User->exp
                ]
            ],
            'time' => date('H:i', time()) 
        ]);
    }
    public function quit($uuid) {
        Session::where([['user_id', '=', $uuid], ['place', '=', 'Launcher']])->first()->delete();
    }
}
