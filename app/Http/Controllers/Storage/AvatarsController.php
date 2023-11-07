<?php
 
namespace App\Http\Controllers\Storage;

use App\Helpers\Lang;
use App\Helpers\Response;

use App\Enums\Errors;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
class AvatarsController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function name(String $name){
        return Response::data(User\Instance::where('name', $name)->select('avatar', 'skin', 'params', 'id')->first()->avatar());
    }
    public function me(){
        return Response::data(Auth::user()->avatar());
    }
}