<?php
 
namespace App\Http\Controllers;

use App\Helpers\Lang;

use App\Http\Controllers\Controller;
use App\Models\Role;
 
class RolesController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function getAll()
    {
        if(count(Role::all()) == 0){
            return response()->json(['response' => 404, 'error' => Lang::get('api.roles.notfoundone')], 404);
        }
        return response()->json(['response' => 200, 'data' => Role::all(), 'time' => date('H:i', time()) ]);
    }
    public function getByUUID($uuid)
    {
        if($info = Role::getByUUID($uuid) == NULL){
            return response()->json(["response" => 404, "error" => Lang::get('api.roles.notfound')], 404);
        }
        return response()->json(['response' => 200, 'data' => Role::getByUUID($uuid), 'time' => date('H:i', time()) ]);
    }
    public function getByID($id)
    {
        if($info = Role::getByID($id) == NULL){
            return response()->json(["response" => 404, "error" => Lang::get('api.roles.notfound')], 404);
        }
        return response()->json(['response' => 200, 'data' => Role::getByID($id), 'time' => date('H:i', time()) ]);
    }
    public function getByName($name)
    {
        if($info = Role::getByName($name) == NULL){
            return response()->json(["response" => 404, "error" => Lang::get('api.roles.notfound')], 404);
        }
        return response()->json(['response' => 200, 'data' => Role::getByName($name), 'time' => date('H:i', time()) ]);
    }
}