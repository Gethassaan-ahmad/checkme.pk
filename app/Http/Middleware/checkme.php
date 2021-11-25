<?php

namespace App\Http\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\Token;
use Closure;
use GuzzleHttp\Psr7\Message;
use Illuminate\Http\Request;

class checkme
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $getToken = $request->bearerToken(); 
        $keyValue = config('constant.keyValue');
        $decoded = JWT::decode($getToken, new Key($keyValue,"HS256"));
        $uID = $decoded->id;

        $TokenExist = Token::where('token',$getToken)->first();

        if (!isset($TokenExist)) {
            // return "token doesnot exist";
            return response([
                "mesage" => "token not exists"
            ]);
            
        }else {
                return $next($request);
        }
    }

}
