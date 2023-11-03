<?php
 
namespace App\Http\Controllers\Storage;

use App\Helpers\Lang;
use App\Helpers\Response;

use App\Enums\Errors;

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
        return Response::data(User\Instance::where('name', $name)->select('skin', 'id')->first()->skin());
    }
    public function choose(Request $request){
        $validator = Validator::make($request->all(), [
            'skin' => ['required']
        ], [
            'required' => __('register')["errors"]['required']
        ]);
        if ($validator->fails()){
            $errors = $validator->errors();
            return Response::error(ERRORS::CLIENT_VALIDATION, $errors->all(':message')[0]);
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
        return Response::data(['skin' => ["path" => $path, "type" => $type]]);
    }
    public function upload(Request $request){
        if(!$request->hasFile('skin')) {
            return Response::error(ERRORS::CLIENT_VALIDATION, Lang::get('api.storage.skins.notfound'));
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
            return Response::error(ERRORS::CLIENT_VALIDATION, $errors->all(':message')[0]);
         }
         if($request->type == "default" || $request->type == "slim"){
            $user = Auth::user();
            $request->file('skin')->storeAs('/users/'.$user->id.'/', 'skin.png');
            $user->setSkin($request->type);
            return Response::data(['skin' => $user->skin()]);
         }
         return Response::error(ERRORS::CLIENT_VALIDATION, Lang::get("api.storage.skins.type"));
}
}