<?php

namespace App\Models\Pin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class Password extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['email'];

    protected $hidden = ['pin'];

    protected $casts = ['created_at' => 'datetime:Y-m-d H:m:s'];

    public $timestamps = false;

    protected $table = 'Password_resets';
    protected $connection = 'Site';

    protected function generate(String $email, Mail $mail): void{
        $pin = rand(10000, 99999);
        Mail::to($email)->send(new $mail($pin));
        Password::create([
            "email" => $email,
            "pin" => $pin
        ]);
    }
    protected function regenerate(String $email, Mail $mail): void{
        Password::where("email", $email)->first()->delete();
        $pin = rand(10000, 99999);
        Mail::to($email)->send(new $mail($pin));
        Password::create([
            "email" => $email,
            "pin" => $pin
        ]);
    }

    
    protected function Check(String $email, int $pin){
        return Password::where("email", $email)->where("pin", $pin)->first();
    }

    protected function Erase(String $email, int $pin = NULL){
        return $pin ? Password::where("email", $email)->first()->delete() : Email::where("email", $email)->first()->delete();
    }
}
