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
 
class CloaksController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function name($name)
    {   
        return Response::data(User\Instance::where('name', $name)->select('cloak', 'id')->first()->cloak());
    }
    public function upload(Request $request){
        if(!$request->hasFile('cloak')) {
            return Response::error(ERRORS::CLIENT_VALIDATION, Lang::get('api.storage.skins.notfound'));
        }
        $file = $request->file('cloak'); 
        $validator = Validator::make($request->all(), [
            'cloak' => ['image' => 'required', 'mimes:png', File::image()->dimensions(Rule::dimensions()->maxWidth(1024)->maxHeight(512))]
        ], [
            'cloak.mimes' => Lang::get("api.storage.cloaks.invalid"),
            'cloak.dimensions' => Lang::get("api.storage.cloaks.bigsize"),
            'required' => __('register')["errors"]['required']
        ]);
        if ($validator->fails()){
            $errors = $validator->errors();
            return Response::error(ERRORS::CLIENT_VALIDATION, $errors->all(':message')[0]);
         }
         $user = Auth::user();
         $request->file('cloak')->storeAs('/users/'.$user->id.'/', 'cloak.png');
         $user->setCloak();
         return Response::data(['cloak' => $user->cloak()]);
}
}