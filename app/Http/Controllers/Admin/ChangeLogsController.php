<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

// use App\Models\Audit;


use App\Models\ChangeLog\Item;

class ChangeLogsController extends Controller
{
    public function create(Request $request){
        $data = $request->all();
        if($this->validator($data)->fails()){
            $errors = $this->validator($request->all())->errors();
            return \Response::json([
                'response' => 400,
                'error' => $errors->all(':message')[0],
                'time' => date('H:i', time()) 
            ]);
        }
        $changelog = Item::create([
            'title' => $data['title'],
            'author' => $data['author'],
            'description' => $data["description"],
            'content' => $data["content"],
            'comment' => $data["comment"],
        ]);
        return response()->json([
            'response' => 200,
            'message' => 'ChangeLog успешно выпущен!',
            'changelog_id' => $changelog->id,
            'req_id' => "AUDIT IN DEVELOPMENT",
            'time' => date('H:i', time()) 
        ]);
    }   

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'title' => ['required', 'string'],
            'author' => ['required', 'uuid', 'exists:Users,id'],
            'description' => ['required', 'string', 'max: 500'],
            'content' => ['required', 'string'],
            'comment' => ['required', 'string', 'max: 120']
        ], [
            'author.exists' => 'Нет такого пользователя. Проверьте ваш запрос, или отпишитесь разработчику.',

            'description.max' => 'Максимальная длинна краткого описания - 500 символов!',

            'comment.max' => 'Максимальная длинна комментария - 120 символов!',

            'required' => 'Все поля обязательны к заполнению!'
        ]);
    }
}
