<?php
 
namespace App\Http\Controllers\Storage;

use App\Helpers\Lang;

use App\Http\Controllers\Controller;
use App\Models\User;
use Validator;
 
class SkinsController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function name($name)
    {      
        return response()->json(['response' => 200, 'data' => User::where('name', $name)->select('skin', 'id')->first()->skin(), 'time' => date('H:i', time()) ]);
    }
    public function upload(){
        if(!$request->hasFile('fileName')) {
            return response()->json(['upload_file_not_found'], 400);
        }
     
        $allowedfileExtension=['pdf','jpg','png'];
        $files = $request->file('fileName'); 
        $errors = [];
     
        foreach ($files as $file) {      
     
            $extension = $file->getClientOriginalExtension();
     
            $check = in_array($extension,$allowedfileExtension);
     
            if($check) {
                foreach($request->fileName as $mediaFiles) {
     
                    $path = $mediaFiles->store('public/images');
                    $name = $mediaFiles->getClientOriginalName();
                }
            } else {
                return response()->json(['invalid_file_format'], 422);
            }
     
            return response()->json(['file_uploaded'], 200);
    }
}
}