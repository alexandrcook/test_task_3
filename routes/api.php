<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{BlogController, BlogPostController, PostCommentController};
use App\Http\Controllers\Auth\{CustomRegisterController, CustomLoginController};

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

Route::post('/register', [CustomRegisterController::class, 'register'])->name('api.register');
Route::post('/login', [CustomLoginController::class, 'login'])->name('api.login');

Route::resource('blogs', BlogController::class)->only(['index','show']);
Route::resource('blogs.posts', BlogPostController::class)->shallow()->only(['index','show']);
Route::resource('posts.comments', PostCommentController::class)->shallow()->only(['index']);

Route::group(['middleware' => 'auth:sanctum'], function (){
    Route::resource('blogs', BlogController::class)->only(['store']);
    Route::resource('blogs.posts', BlogPostController::class)->shallow()->only(['store']);
    Route::resource('posts.comments', PostCommentController::class)->shallow()->only(['store']);
});

Route::group(['middleware' => ['auth:sanctum', 'ability:admin']], function (){
    Route::resource('blogs', BlogController::class)
        ->only(['destroy', 'delete', 'restore', 'trashed']);
    Route::resource('blog.posts', BlogPostController::class)
        ->shallow()->only(['destroy', 'delete', 'restore', 'trashed']);
    Route::resource('comments', PostCommentController::class)
        ->shallow()->only(['destroy', 'delete', 'restore', 'trashed']);
});
