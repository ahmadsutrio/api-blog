<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register',[UserController::class,'register']);
Route::post('/login',[UserController::class,'login']);
Route::post('/logout',[UserController::class,'logout'])->middleware('auth:sanctum');

// Posts
Route::get('/posts',[PostController::class,'index'])->middleware('auth:sanctum');
Route::get('/posts/{id}',[PostController::class,'show'])->middleware('auth:sanctum');
Route::post('/posts',[PostController::class,'store'])->middleware('auth:sanctum');
Route::put('/posts/{id}',[PostController::class,'update'])->middleware('auth:sanctum');
Route::delete('/posts/{id}',[PostController::class,'destroy'])->middleware('auth:sanctum');

