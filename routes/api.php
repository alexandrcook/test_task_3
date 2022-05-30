<?php

use Illuminate\Support\Facades\Route;

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

Route::post('/register', [\App\Http\Controllers\Auth\CustomRegisterController::class, 'register'])->name('api.register');
Route::post('/login', [\App\Http\Controllers\Auth\CustomLoginController::class, 'login'])->name('api.login');

Route::resource('blogs', \App\Http\Controllers\BlogController::class)
    ->only(['index','show']);

Route::resource('posts', \App\Http\Controllers\PostController::class)
    ->only(['index','show']);


Route::group(['middleware' => 'auth:sanctum'], function (){
    //get blogs list for specific user
    Route::post('/user/blogs', [\App\Http\Controllers\BlogController::class, 'getUserBlogs']);

    Route::resource('blogs', \App\Http\Controllers\BlogController::class)
        ->except(['index','show','destroy', 'delete', 'restore']);

    Route::resource('posts', \App\Http\Controllers\PostController::class)
        ->except(['index','show','destroy', 'delete', 'restore']);

    Route::resource('posts.comments', \App\Http\Controllers\PostCommentController::class)
    ->only(['store']);

});

Route::group(['middleware' => ['auth:sanctum', 'ability:admin']], function (){

    Route::resource('blogs', \App\Http\Controllers\BlogController::class)
        ->only(['destroy', 'delete', 'restore']);

    Route::resource('posts', \App\Http\Controllers\PostController::class)
        ->only(['destroy', 'delete', 'restore']);

    Route::resource('comments', \App\Http\Controllers\CommentController::class)
        ->only(['destroy', 'delete', 'restore']);

    Route::post('/trashed', [\App\Http\Controllers\TrashController::class, 'getTrashedItems']);
});
