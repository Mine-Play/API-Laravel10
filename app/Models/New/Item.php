<?php

namespace App\Models\New;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\New;

class Item extends Model
{
    use HasUuids;
    
    public $timestamps = false;
    protected $table = 'News';
    protected $connection = 'Site';

    protected $fillable = [
        'title',
        'subtitle',
        'content',
        'time',
        'author',
        'category'
    ];

    protected $attributes = [
        'views' => 0,
        'likes' => 0,
        'vote' => null,
        'anchors' => null
    ];

    protected $casts = [
        'date'  => 'datetime:Y-m-d H:00',
        'vote' => 'array',
        'anchors' => 'array'
    ];

    public function User()
    {
        return $this->belongsTo(User\Instance::class, 'author');
    }

    public static function getAll(){
        $select = Item::select('id', 'title', 'subtitle', 'time', 'author', 'views', 'likes', 'date', 'vote', 'anchors', 'category')->get();
        foreach($select as $key => $s){
            $select[$key]->author = [
                "id" => $s->author,
                "name" => User\instance::select("name")->where('id', $s->author)->first()->name
            ];
            $select[$key]->category = [
                "id" => $s->category,
                "name" => New\Category::select("name", "id")->where('id', $s->category)->first()
            ];
        }
        return $select;
    }
    public static function getByID($id){
        $select = Item::where('id', $id)->first();
        if($select == null){
            return null;
        }
        $select->views += 1;
        $select->save();
        $user = User\instance::select("name", "id")->where('id', $select->author)->first();
        $category = New\Category::select("name", "id")->where('id', $select->category)->first();
        $select->author = [
            "id" => $user->id,
            "name" => $user->name
        ];
        $select->category = [
            "id" => $category->id,
            "name" => $category->name
        ];
        return $select;
    }

    public function like(){
        $user = Auth::user();
        if($user->likes != NULL){
            foreach($user->likes as $key => $like){
                if($like == $this->id){
                    $buffer = $user->likes;
                    unset($buffer[$key]);
                    $user->likes = $buffer;
                    $user->save();
                    $this->likes--;
                    $this->save();
                    return $this->likes;
                }
            }
            $user->likes = array_merge($user->likes, array($this->id));
        }else{
            $user->likes = array($this->id);
        }
        $user->save();
        $this->likes++;
        $this->save();
        return $this->likes;
    }
}
