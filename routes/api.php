<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/**********************************   Login & Regidter & Logout   *******************************************/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

/**********************************   Posts Route    *******************************************/
Route::get('/posts', [PostController::class,'index'])->middleware('auth:sanctum');
Route::post('/posts/store',[PostController::class,'store'])->middleware('auth:sanctum');
Route::post('/posts/up/{id}',[PostController::class,'update'])->middleware('auth:sanctum');
Route::get('/posts/{id}/show',[PostController::class,'show'])->middleware('auth:sanctum');
Route::post('/posts/del/{id}',[PostController::class,'destroy'])->middleware('auth:sanctum');

/**********************************   Category Route  *******************************************/
Route::get('/categories',[CategoryController::class,'index'])->middleware('auth:sanctum');
Route::post('/category/store',[CategoryController::class,'store'])->middleware('auth:sanctum');
Route::get('/category/{id}/show',[CategoryController::class,'show']);
Route::post('/category/up/{id}',[CategoryController::class,'update'])->middleware('auth:sanctum');
Route::post('/category/del/{id}',[CategoryController::class,'destroy'])->middleware('auth:sanctum');

/**********************************   Tags Route  *******************************************/
Route::get('/tags',[TagController::class,'index'])->middleware('auth:sanctum');
Route::post('/tag/store',[TagController::class,'store'])->middleware('auth:sanctum');
Route::get('/tag/{id}/show',[TagController::class,'show']);
Route::post('/tag/up/{id}',[TagController::class,'update'])->middleware('auth:sanctum');
Route::post('/tag/del/{id}',[TagController::class,'destroy'])->middleware('auth:sanctum');


/**********************************   Comment Route   *******************************************/
Route::get('/comments',[CommentController::class,'index'])->middleware('auth:sanctum');
Route::post('/comment/check/comment',[CommentController::class,'checkComment'])->middleware('auth:sanctum');
Route::post('/comment/check/post',[CommentController::class,'checkPost'])->middleware('auth:sanctum');
Route::post('/comment/store',[CommentController::class,'store'])->middleware('auth:sanctum');
Route::get('/comment/{id}/show',[CommentController::class,'show']);
Route::post('/comment/{id}/update',[CommentController::class,'update'])->middleware('auth:sanctum');
Route::post('/comment/del/{id}',[CommentController::class,'destroy'])->middleware('auth:sanctum');