<?php

namespace App\Http\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\Token;
use Closure;
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

        $decoded = JWT::decode($getToken, new Key("checkMe","HS256"));
        $userID = $decoded->data;
        $userExist = Token::where("userID",$userID)->first();
        if($userExist)
        {
            $userExist->delete();
        }
        else{
            return response([
                "message" => "This user is already logged out"
            ], 404);
        }

        return response([
            "message" => "logout successfull"
        ], 200);
    }
}
