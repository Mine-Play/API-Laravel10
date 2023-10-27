<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\New;

class NewsController extends Controller
{
    public function create(Request $request){
        
    }
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'title' => ['required', 'string'],
            'subtitle' => ['required', 'string', 'max: 100'],
            'content' => ['required', 'string'],
            'author' => ['required', 'uuid', 'exists:Users,id'],
            'author' => ['required', 'uuid', 'exists:Users,id'],
            'author' => ['required', 'uuid', 'exists:Users,id'],
            'author' => ['required', 'uuid', 'exists:Users,id'],
        ], [
            'author.exists' => 'Нет такого пользователя. Проверьте ваш запрос, или отпишитесь разработчику.',

            'description.max' => 'Максимальная длинна краткого описания - 500 символов!',

            'comment.max' => 'Максимальная длинна комментария - 120 символов!',

            'required' => 'Все поля обязательны к заполнению!'
        ]);
    }
}
