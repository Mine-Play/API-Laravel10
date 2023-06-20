<?php

namespace App\Helpers;
use Illuminate\Support\Str;

class Lang {
    private static function variable($line, array $replace) {
    if (empty($replace)) return $line;

    $shouldReplace = [];

    foreach ($replace as $key => $value) {
        $shouldReplace[':'.Str::ucfirst($key)] = Str::ucfirst($value);
        $shouldReplace[':'.Str::upper($key)] = Str::upper($value);
        $shouldReplace[':'.$key] = $value;
    }

    return strtr($line, $shouldReplace);
}   
    public static function get($path, $vars = "") {
        $properties = explode(".", $path);
        $base = __($properties[0]);
        unset($properties[0]);
        foreach($properties as $property) {
            if(is_numeric($property)) {
                $property = intval($property);
            }
            try {
                $base = $base[$property];
            } catch (\Throwable $th) {
                return $path;
            }
        }
        if($vars == ""){
            return $base;
        }
        $lang = Lang::variable($base, $vars);
        return $lang;
}
}
