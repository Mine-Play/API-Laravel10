<?php
 
namespace App\Http\Controllers\Storage;

use App\Helpers\Lang;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
 
class SkinsController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function name($name)
    {      
        return response()->json(['response' => 200, 'data' => User::where('name', $name)->select('skin', 'id')->first()->skin(), 'time' => date('H:i', time()) ]);
    }
    public function choose(Request $request){
        $validator = Validator::make($request->all(), [
            'skin' => ['required']
        ], [
            'required' => __('register')["errors"]['required']
        ]);
        if ($validator->fails()){
            $errors = $validator->errors();
            return \Response::json([
                'response' => 4002,
                'error' => $errors->all(':message')[0],
                'time' => date('H:i', time()) 
            ]);
         }
        switch($request->skin){
            case "steve":
                $user = Auth::user();
                $user->setSkin(null, 2, array("skin" => ["type" => "slim", "name" => "steve"]));

                $path = Storage::url('/users/default/skins/steve.png');
                $type = "default";
                break;
            case "play":
                $user = Auth::user();
                $user->setSkin(null, 2, array("skin" => ["type" => "slim", "name" => "play"]));

                $path = Storage::url('/users/default/skins/play.png');
                $type = "slim";
                break;
            default:
                $user = Auth::user();
                $user->setSkin(null, 2, array("skin" => ["type" => "slim", "name" => "steve"]));

                $path = Storage::url('/users/default/skins/steve.png');
                $type = "default";
                break;
        }
        return \Response::json([
            'response' => 200,
            'skin' => [
                 "path" => $path,
                 "type" => $type
            ],
            'time' => date('H:i', time()) 
        ]);
    }
    public function upload(Request $request){
        if(!$request->hasFile('skin')) {
            return \Response::json([
                'response' => 4001,
                'error' => Lang::get('api.storage.skins.notfound'),
                'time' => date('H:i', time()) 
            ]);
        }
        $file = $request->file('skin'); 
        $validator = Validator::make($request->all(), [
            'skin' => ['image' => 'required', 'mimes:png', File::image()->dimensions(Rule::dimensions()->maxWidth(1024)->maxHeight(512))],
            'type' => ['required']
        ], [
            'skin.mimes' => Lang::get("api.storage.skins.invalid"),
            'skin.dimensions' => Lang::get("api.storage.skins.bigsize"),
            'required' => __('register')["errors"]['required']
        ]);
        if ($validator->fails()){
            $errors = $validator->errors();
 
            return \Response::json([
                'response' => 4002,
                'error' => $errors->all(':message')[0],
                'time' => date('H:i', time()) 
            ]);
         }
         if($request->type == "default" || $request->type == "slim"){
            $user = Auth::user();
            $path = $request->file('skin')->storeAs('/users/'.$user->id.'/skins', 'skin.png');
            $user->setSkin($request->type);
            return \Response::json([
               'response' => 200,
               'skin' => [
                    "path" => $path,
                    "type" => $request->type
               ],
               'time' => date('H:i', time()) 
           ]);
         }
         return \Response::json([
            'response' => 4003,
            'error' => Lang::get("api.storage.skins.type"),
            'time' => date('H:i', time()) 
        ]);
}
}