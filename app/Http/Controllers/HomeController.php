<?php
 
namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use App\Helpers\Lang;
 
class HomeController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        return response()->json([
            'status' => 200,
            'message' => Lang::get('api.welcome'),
            'php' => phpversion(),
            'time' => date('H:i', time()) 
        ]);
    }
}