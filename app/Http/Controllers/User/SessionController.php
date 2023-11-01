<?php
 
namespace App\Http\Controllers\User;

use App\Helpers\Lang;
use App\Helpers\Response;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
 
class SessionController extends Controller
{
    public function sessions(){
        $user = Auth::user();
        return Response::data($user->Sessions);
    }

    public function endSession(){
        $user = Auth::user();
        foreach ($user->Sessions as $session){
            $session->kill();
        }
        return Response::success();
    }
}