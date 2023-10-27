<?php
 
namespace App\Http\Controllers;

use App\Helpers\Lang;

use App\Http\Controllers\Controller;
use App\Models\New;
 
class NewsController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function getAll()
    {
        return response()->json(['response' => 200, 'data' => New\Item::getAll(), 'time' => date('H:i', time()) ]);
    }
    // public function getByUUID($uuid)
    // {
    //     if($info = New\Item::getByUUID($uuid) == NULL){
    //         return response()->json(["response" => 404, "error" => Lang::get('api.news.notfound')], 404);
    //     }
    //     return response()->json(['response' => 200, 'data' => New\Item::getByUUID($uuid), 'time' => date('H:i', time()) ]);
    // }
    public function getByID($id)
    {
        $info = New\Item::getByID($id);
        if($info == NULL){
            return response()->json(["response" => 404, "error" => Lang::get('api.news.notfound')], 404);
        }
        return response()->json(['response' => 200, 'data' => $info, 'time' => date('H:i', time()) ]);
    }
    public function like($id){
        $likes = New\Item::select('id', 'likes')->where('id', $id)->first()->like();
        return response()->json(['response' => 200, 'data' => ['likes' => $likes], 'time' => date('H:i', time()) ]);
    }
}