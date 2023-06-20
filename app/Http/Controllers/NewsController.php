<?php
 
namespace App\Http\Controllers;

use App\Helpers\Lang;

use App\Http\Controllers\Controller;
use App\Models\News;
 
class NewsController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function getAll()
    {
        return response()->json(['response' => 200, 'data' => News::getAll(), 'time' => date('H:i', time()) ]);
    }
    public function getByUUID($uuid)
    {
        if($info = News::getByUUID($uuid) == NULL){
            return response()->json(["response" => 404, "error" => Lang::get('api.news.notfound')], 404);
        }
        return response()->json(['response' => 200, 'data' => News::getByUUID($uuid), 'time' => date('H:i', time()) ]);
    }
    public function getByID($id)
    {
        if($info = News::getByID($id) == NULL){
            return response()->json(["response" => 404, "error" => Lang::get('api.news.notfound')], 404);
        }
        return response()->json(['response' => 200, 'data' => News::getByID($id), 'time' => date('H:i', time()) ]);
    }
}