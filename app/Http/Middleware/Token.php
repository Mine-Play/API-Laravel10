<?php
 
namespace App\Http\Middleware;
 
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;

class Token
{
    public function handle(Request $request, Closure $next, string $source): Response
    {
        $response = $next($request);
        switch($source){
            case "launcher":
                if($request->bearerToken() === "txsbDg{wC9JZwF8G~3A7GLMPKC1myXnK"){
                    return $response;
                }
                break;
        }
        abort(404);
    }
}