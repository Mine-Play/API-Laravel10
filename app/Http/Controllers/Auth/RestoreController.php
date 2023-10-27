<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

use App\Mail\RestorePassword;
use Carbon\Carbon;

use App\Models\User;

class RestoreController extends Controller
{
    public function send(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'max:500', 'exists:Users,email'],
        ]);
    
        if ($validator->fails()) {
            $errors = $validator->errors();
            return \Response::json([
                'response' => 4001,
                'error' => $errors->all(':message')[0],
                'time' => date('H:i', time()) 
            ]);
        }
        $select = DB::connection('Site')->table('Password_resets')->where('email', $request->email)->whereDate('created_at', '<=', Carbon::now()->toDateTimeString())->first();
        if($select == null){
            $pin = random_int(10000, 99999);
            DB::connection('Site')->table('Password_resets')
            ->insert(
                [
                    'email' => $request->all()['email'], 
                    'pin' => $pin
                ]
            );
            Mail::to($request->all()['email'])->send(new RestorePassword($pin));   
            return \Response::json([
                'response' => 200,
                'type' => 'new',
                'time' => date('H:i', time()) 
            ]);
        }else{
            return \Response::json([
                'response' => 200,
                'type' => 'old',
                'time' => date('H:i', time()) 
            ]);
        }
    }
    public function verify(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'max:500', 'exists:Users,email'],
            'pin' => ['required', 'integer', 'min:10000', 'max:99999'],
        ]);
    
        if ($validator->fails()) {
            $errors = $validator->errors();
            return \Response::json([
                'response' => 4001,
                'error' => $errors->all(':message')[0],
                'time' => date('H:i', time()) 
            ]);
        }
        $select = DB::connection('Site')->table('Password_resets')->where('email', $request->email)->where('pin', $request->pin)->whereDate('created_at', '<=', Carbon::now()->toDateTimeString())->first();
        if($select == null){
            return \Response::json([
                'response' => 4002,
                'error' => "Invalid pin",
                'time' => date('H:i', time()) 
            ]);
        }
        return \Response::json([
            'response' => 200,
            'time' => date('H:i', time()) 
        ]);
    }
    public function change(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'max:500', 'exists:Users,email'],
            'pin' => ['required', 'integer', 'min:10000', 'max:99999'],
            'password' => ['required', 'string', 'min:8', 'max:40']
        ]);
    
        if ($validator->fails()) {
            $errors = $validator->errors();
            return \Response::json([
                'response' => 4001,
                'error' => $errors->all(':message')[0],
                'time' => date('H:i', time()) 
            ]);
        }
        $select = DB::connection('Site')->table('Password_resets')->where('email', $request->email)->where('pin', $request->pin)->whereDate('created_at', '<=', Carbon::now()->toDateTimeString())->first();
        if($select == null){
            return \Response::json([
                'response' => 4002,
                'error' => "Invalid pin",
                'time' => date('H:i', time()) 
            ]);
        }
        $user = User\Instance::where('email', $request->email)->first();
        $user->changePassword($request->password);
        foreach ($user->Sessions as $session){
            $session->kill();
        }
        DB::connection('Site')->table('Password_resets')->where('email', $request->email)->where('pin', $request->pin)->delete();
        return \Response::json([
            'response' => 200,
            'time' => date('H:i', time()) 
        ]);
    }
}
