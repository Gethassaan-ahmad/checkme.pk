<?php 
use App\Http\Controllers\MainController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post("/register", [MainController::class, "register"]);
