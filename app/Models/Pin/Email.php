<?php

namespace App\Models\Pin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

use Carbon\Carbon;

class Email extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['email', 'pin'];

    protected $hidden = ['pin'];

    protected $casts = ['created_at' => 'datetime:Y-m-d H:m:s'];

    public $timestamps = false;

    protected $table = 'Email_Confirmations';
    protected $connection = 'Site';

    protected function Generate(String $email, String $mail): void{
        $pin = rand(10000, 99999);
        Mail::to($email)->send(new $mail($pin));
        Email::create([
            "email" => $email,
            "pin" => $pin
        ]);
    }
    protected function Regenerate(String $email, Mail $mail): void{
        Email::where("email", $email)->first()->delete();
        $pin = rand(10000, 99999);
        Mail::to($email)->send(new $mail($pin));
        Email::create([
            "email" => $email,
            "pin" => $pin
        ]);
    }

    protected function Check(String $email, int $pin = NULL){
        return $pin ? Email::where("email", $email)->where("pin", $pin)->whereDate('created_at', '<=', Carbon::now()->toDateTimeString())->first() : Email::where("email", $email)->whereDate('created_at', '<=', Carbon::now()->toDateTimeString())->first();
    }

    protected function Erase(String $email, int $pin = NULL){
        return $pin ? Email::where("email", $email)->first()->delete() : Email::where("email", $email)->first()->delete();
    }
}
