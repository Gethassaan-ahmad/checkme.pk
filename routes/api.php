<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\MainController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\postsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post("/register", [MainController::class, "register"]);
Route::post("/login", [MainController::class, "login"]);

Route::middleware(['protectedpage'])->group (function()
{
    Route::post("logout/", [MainController::class, "logout"]);
    Route::post("posts/", [postsController::class, "create"]);
    Route::post("posts/{id}", [postsController::class, "destroy"]);
    
    Route::post("comment", [CommentController::class, "create"]);
    Route::post("commentUpdate/{id}", [CommentController::class, "update"]);
    Route::delete("commentDelete/{id}", [CommentController::class, "destroy"]);

    // Route::post('/posts','postsController');  
});



