<?php

namespace App\Http\Controllers;

use App\Helpers\Lang;

use App\Http\Controllers\Controller;
use App\Models\Server;

class ServersController extends Controller
{
    public function getAll()
    {
        return response()->json(['response' => 200, 'data' => Server::getAll(), 'time' => date('H:i', time()) ]);
    }
    public function getByUUID($uuid){
        if(Server::getByUUID($uuid) == NULL){
            return response()->json(["response" => 404, "error" => Lang::get('api.servers.notfound')], 404);
        }
        return response()->json(['response' => 200, 'data' => Server::getByUUID($uuid), 'time' => date('H:i', time()) ]);
    }
    public function getByID($id){
        if(Server::getByID($id) == NULL){
            return response()->json(["response" => 404, "error" => Lang::get('api.servers.notfound')], 404);
        }
        return response()->json(['response' => 200, 'data' => Server::getByID($id), 'time' => date('H:i', time()) ]);
    }
    public function getBySlug($slug){
        if(Server::getBySlug($slug) == NULL){
            return response()->json(["response" => 404, "error" => Lang::get('api.servers.notfound')], 404);
        }
        return response()->json(['response' => 200, 'data' => Server::getBySlug($slug), 'time' => date('H:i', time()) ]);
    }
    public function getGlobalOnline(){
        return response()->json(['response' => 200, 'data' => Server::getGlobalOnline(), 'time' => date('H:i', time()) ]);
    }
    public function getByName($name){
        if(Server::getByName($name) == NULL){
            return response()->json(["response" => 404, "error" => Lang::get('api.servers.notfound')], 404);
        }
        return response()->json(['response' => 200, 'data' => Server::getByName($name), 'time' => date('H:i', time())]);
    }
}