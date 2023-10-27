<?php
 
namespace App\Http\Controllers;

use App\Helpers\Lang;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Wallet;
use Illuminate\Support\Facades\Redis;

 
class WalletsController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function me()
    {
        $user = Auth::user();
        return response()->json(['response' => 200, 'data' => Wallet\Instance::wid($user->wid->id), 'time' => date('H:i', time()) ]);
    }
    public function getByWID($wid)
    {
        if(Wallet::wid($wid) == NULL){
            return response()->json(["response" => 404, "error" => Lang::get('api.wallet.notfound')], 404);
        }
        return response()->json(['response' => 200, 'data' => Wallet\Instance::wid($wid), 'time' => date('H:i', time()) ]);
    }

    public function addme(Request $request){
        $validator = Validator::make($request->all(), [
            'amount' => ['required', 'integer', "min:1"]
        ], [
            'amount.min' => Lang::get('api.wallet.minsum'),
            'required' => __('register')["errors"]['required']
        ]);
        if ($validator->fails()){
            $errors = $validator->errors();
            return response()->json(['response' => 4001, 'error' => $errors->all(':message')[0], 'time' => date('H:i', time()) ]);
        }
        $amount = $request->all()["amount"];
        $user = Auth::user();
        $user->addMoney($amount);
        Redis::publish('api_user_wallet_add', json_encode([
            'uniqueId' => $user->id,
            'wallet' => 'money',
            'amount' => $amount
         ]));
        return response()->json(['response' => 200, 'message' => Lang::get('api.wallet.successadd', ["amount" => $amount]), 'data' => ["balance" => $user->Wallet->money], 'time' => date('H:i', time()) ]);
    }
    public function addPlayer(Request $request) {
        $data = $request->all();
        $validator = Validator::make($data, [
            'user' => ['required', 'uuid', "exists:Users:id"],
            'amount' => ['required', 'integer', "min:1"]
        ], [
            'amount.min' => Lang::get('api.wallet.minsum'),
            'user.exists' => Lang::get('api.wallet.usernotfound', ["id" => $data["user"]]),
            'required' => __('register')["errors"]['required']
        ]);
        if ($validator->fails()){
            $errors = $validator->errors();
            return response()->json(['response' => 4001, 'error' => $errors->all(':message')[0], 'time' => date('H:i', time()) ]);
        }
        
    }
    public function exchange(Request $request){
        $validator = Validator::make($request->all(), [
            'amount' => ['required', 'integer', "min:1"],
            'vault' => ['required', 'string']
        ], [
            'amount.min' => Lang::get('api.wallet.minsum'),
            'required' => __('register')["errors"]['required']
        ]);
        if ($validator->fails()){
            $errors = $validator->errors();
            return response()->json(['response' => 4001, 'error' => $errors->all(':message')[0], 'time' => date('H:i', time()) ]);
        }
        $user = Auth::user();
        if($user->Wallet->money < $request->all()["amount"]){
            return response()->json(['response' => 4003, 'error' => "У вас недостаточно монет для обмена.", 'time' => date('H:i', time()) ]);
        }
        if($request->all()["vault"] == "keys" || $request->all()["vault"] == "coins"){
            $wallet = $user->Wallet->exchange($request->all()["vault"], $request->all()["amount"]);
            return response()->json(['response' => 200, 'data' => $wallet, 'time' => date('H:i', time()) ]);
        }
        return response()->json(['response' => 4002, 'error' => "Некорректная валюта", 'time' => date('H:i', time()) ]);
    }

    public function history(){
        $user = Auth::user();
        return response()->json(['response' => 200, 'data' => $user->Wallet->History, 'time' => date('H:i', time()) ]);
    }
}