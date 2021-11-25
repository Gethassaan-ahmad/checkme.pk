<?php

namespace App\Http\Controllers;

// use Egulias\EmailValidator\Warning\Comment;
use Illuminate\Http\Request;
use App\Models\Comments;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class CommentController extends Controller
{
    public function create(Request $request)
    {
        $getToken = $request->bearerToken();

        // if (!isset($getToken)) {

        //     return response([
        //     'message'=>"bearer token not found" 
        // ]);
        // dd('sdsd');
        $keyValue = config('constant.keyValue');
        $decoded = JWT::decode($getToken, new Key($keyValue,"HS256"));

        $userid = $decoded->data;
        // echo "hsdia";
        $comments = new Comments();
        $comments->user_id = $userid;
        $comments->post_id = $request->input('post_id');
        $comments->comments = $request->input('comment');
        $comments->attachment = $request->input('attachment');
        

        $comments->save();
        return response()->json($comments);
    }
    



    public function destroy($id)
    {
        // SELECT * post WHERE id = url_wali_id AND user_id = Login_bndy_id; 
        // SELECT * post WHERE id = 1 AND user_id = 2 == null; 

         Comments::where('id', $id)->delete();
            return response([
                'message'=>"deleted sucessfully"
            ]);
    }

    public function update(Request $request, $id)
    {
        $updated = Comments::find($id);
        $updated->update($request->all());
        return response([
            'Update'=>$updated,
        ]);
    }
}
