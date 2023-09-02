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
                if($request->bearerToken() === env('API_LAUNCHER_KEY')){
                    return $response;
                }
                break;
            case "server":
                if($request->bearerToken() === env('API_SERVER_KEY')){
                    return $response;
                }
                break;
        }
        abort(404);
    }
}