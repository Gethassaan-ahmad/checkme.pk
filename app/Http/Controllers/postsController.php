<?php

namespace App\Http\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use App\Models\posts;
use App\Http\Middleware\checkme;

class postsController extends Controller
{
    // to create posts
    public function create(Request $request)
    {
        $getToken = $request->bearerToken();

        // if (!isset($getToken)) {

        //     return response([
        //     'message'=>"bearer token not found" 
        // ]);
        $decoded = JWT::decode($getToken, new Key("checkMe","HS256"));

        $userid = $decoded->data;
        
        // echo "hsdia";
        $posts = new posts();
        $posts-> userid = $userid;
        $posts->title = $request->input('title');
        $posts->body = $request->input('body');
        $posts->attachement = $request->input('attachement');
        

        $posts->save();
        return response()->json($posts);
    }
    



    public function destroy($id)
    {
        // SELECT * post WHERE id = 1 AND user_id = 2 == null; 

         posts::where('id', $id)->delete();
            return response([
                'message'=>"deleted sucessfully"
            ]);
    }

    public function update(Request $request, $id)
    {
        posts::put('id',$id)->update($request->all());

    }
}
