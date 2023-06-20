<?php
 
namespace App\Http\Controllers;

use App\Helpers\Lang;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallet;

 
class WalletsController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function me()
    {
        $user = Auth::user();
        return response()->json(['response' => 200, 'data' => Wallet::wid($user->wid->id), 'time' => date('H:i', time()) ]);
    }
    public function getByWID($wid)
    {
        if(Wallet::wid($wid) == NULL){
            return response()->json(["response" => 404, "error" => Lang::get('api.wallet.notfound')], 404);
        }
        return response()->json(['response' => 200, 'data' => Wallet::wid($wid), 'time' => date('H:i', time()) ]);
    }
}