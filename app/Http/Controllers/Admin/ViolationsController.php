<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

// use App\Models\Audit;


use App\Models\User;

class ViolationsController extends Controller
{
    public function ban(Request $request){
        $data = $request->all();
        if($this->validator($data)->fails()){
            $errors = $this->validator($request->all())->errors();
            return \Response::json([
                'response' => 400,
                'error' => $errors->all(':message')[0],
                'time' => date('H:i', time()) 
            ]);
        }
        $user = User\Instance::find($data["user"]);
        if(count($user->Violations->where("type", "ban")) != 0){
            return response()->json([
                'response' => 20001,
                'message' => 'Данный пользователь уже заблокирован!',
                'time' => date('H:i', time()) 
            ]);
        }
        $violation = $user->ban($data["moderator"], $data["ending_at"], $data["rules"], $data["message"]);
        return response()->json([
            'response' => 200,
            'message' => 'Пользователь '.$user->name.' забанен',
            'violation_id' => $violation,
            'req_id' => "AUDIT IN DEVELOPMENT",
            'time' => date('H:i', time()) 
        ]);
    }   

    public function unban(Request $request){
        $data = $request->all();
        $user = User\Instance::find($data["user"]);
        if(count($user->Violations->where("type", "ban")) == 0){
            return response()->json([
                'response' => 20001,
                'message' => 'У данного пользоателя нет блокировок!',
                'time' => date('H:i', time()) 
            ]);
        }
        $user->unban($data["moderator"], $data["reason"]);
        return response()->json([
            'response' => 200,
            'message' => 'Пользователь '.$user->name.' разблокирован!',
            'req_id' => "AUDIT IN DEVELOPMENT",
            'time' => date('H:i', time()) 
        ]);
    }   

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'user' => ['required', 'uuid', 'exists:Users,id'],
            'ending_at' => ['required', 'Date'],
            'moderator' => ['required'],
            'rules' => ['required']
        ], [
            'user.exists' => 'Нет такого пользователя. Проверьте ваш запрос, или отпишитесь разработчику.',
            'required' => 'Все поля обязательны к заполнению!'
        ]);
    }
}
