<?php

namespace App\Http\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use App\Models\posts;
use App\Http\Middleware\checkme;
use App\Http\Requests\requestCreatePost;


class postsController extends Controller
{
    // to create posts
    public function create(requestCreatePost $request)
    {
        $getToken = $request->bearerToken();

        $request->validate([

            // 'attachement'  => 'required|file',

        ]);


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
        $request->validated();

        // $collection = (new Mongo())->schooldb->students;
        // $findOne = $collection->findOne(['name' => $name]);


        $data_to_update = [];
        foreach ($request->all() as $key => $value) {
            if (in_array($key, ['title', 'body', ])) {       
                $data_to_update[$key]=$value;
            }
        }
        
        if (isset($findOne)) {
            $id->updateOne(
                ['title' => $title],
                ['$set' => $data_to_update]
            );

            // 'name' => 'Hassan'
            // request->key => request->value


            return response([
                'message' => 'Successfully Updated',
            ]);
        } else {
            return response([
                'message' => 'This Document Not Found',
            ]);
        }
    }
}
