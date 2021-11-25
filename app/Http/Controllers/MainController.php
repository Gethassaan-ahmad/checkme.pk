<?php

namespace App\Http\Controllers;

use App\Mail\sendmail;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\requestRegistration;
use App\Http\Requests\requestlogin;
use Throwable;

class MainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    //Register Action
    public function register(requestRegistration $request)

    {
      try {
        
        
        //Validate the fields
        $fields = $request->validated();

        //Create the user
        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => Hash::make($fields['password'])
        ]);

        Mail::to($request['email'])->send(new Sendmail());
        //Generate token for the user
        //$token = $user->createToken('ProgrammersForce')->plainTextToken;

        $response = [
            'message' => 'User has been created successfully',
            'user' => $user,
            //'token' => $token
        ];

        //Return HTTP 201 status, call was successful and something was created
        return response($response, 201);
    }catch (Throwable $e) {
        return $e->getMessage();
    }
    }

    public function login(requestlogin $request)
    {
        try
        {
        $request = $request->validated();

        // Check Student
        $user = User::where('email', "=" , $request['email'])->first();
        // dd($user->id);
        if(isset($user->id)){
            
            if (Hash::check($request['password'], $user->password)) {
                
                $isLoggedIn = Token::where('userID', $user->id)->first();
                if($isLoggedIn){
                    return response([
                        "message" => "User already logged In"
                    ], 400);
                }   
               
                // Create Token
                $token = $this->createToken($user->id);
                
                // dd($token);
                // saving token table in db
                $saveToken = Token::create([
                    "userID"=>$user->id,
                    "token" => $token
                ]);
                $response = [
                    'status' => 1,
                    'message' => 'Logged in successfully',
                    'user' => $user,
                    'token' => $token
                ];
        
                return response($response, 201);
                
            }else{
                return response([
                    'message' => 'Invalid email or password'
                ], 401);
            }

        }else{
            return response()->json([
                "status"=>0,
                "message"=>"Student not found"
            ],404);
        
        } 

        }catch (Throwable $e) {
            return $e->getMessage();
        }
         
    }

    public function logout(Request $request)
    {
        try
        {
        $getToken = $request->bearerToken(); 
        $keyValue = config('constant.keyValue');
        $decoded = JWT::decode($getToken, new Key($keyValue,"HS256"));
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

    }catch (Throwable $e) {
        return $e->getMessage();
    }

    }

    function createToken($data)

    {
        try
        {
        $key = "checkMe";
        $payload = array(
            "iss" => "http://127.0.0.1:8000",
            "aud" => "http://127.0.0.1:8000/api",
            "iat" => time(),
            "nbf" => 1357000000,
            "data" => $data,
        );
        $jwt = JWT::encode($payload, $key, 'HS256');
        return $jwt;
    }catch (Throwable $e) {
        return $e->getMessage();
    }
    }
        
}
