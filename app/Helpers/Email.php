<?php

namespace App\Helpers;
use Illuminate\Support\Str;

class Email {
    
    public static function obusficate($email) {
        $em   = explode("@",$email);
        $name = implode('@', array_slice($em, 0, count($em)-1));
        $len  = floor(strlen($name)/3);
        return substr($name,0, $len) . str_repeat('*', (strlen($name) - $len)/2) . "@" . end($em);
}
}
