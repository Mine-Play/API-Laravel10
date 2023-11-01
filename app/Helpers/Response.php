<?php

namespace App\Helpers;

use App\Enums\Errors;

use Illuminate\Http\JsonResponse;

class Response {
    public static function data($data, String $msg = NULL): JsonResponse{
        return $msg ? \Response::json(['response' => 2000, 'data' => $data, 'time' => date('H:i', time())]) : \Response::json(['response' => 2000, 'data' => $data, 'message' => $msg, 'time' => date('H:i', time())]);
    }

    public static function error($error, String $msg = NULL): JsonResponse{
        return $msg ? \Response::json(['response' => $error, 'error' => $msg, 'time' => date('H:i', time())]) : \Response::json(['response' => $error, 'time' => date('H:i', time())]);
    }

    public static function success(String $msg = NULL): JsonResponse{
        return \Response::json(['response' => 2000, 'message' => $msg ?? '', 'time' => date('H:i', time())]);
    }
}