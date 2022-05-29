<?php

namespace App\Http\Controllers;

use App\Http\Resources\{BlogResource,CommentResource,PostResource};
use App\Models\{Blog,Comment,Post};

class TrashController extends Controller
{
    public function getTrashedItems()
    {
        return response()->json([
           'trashedBlogs' => BlogResource::collection(Blog::onlyTrashed()->get()),
           'trashedPosts' => PostResource::collection(Post::onlyTrashed()->get()),
           'trashedComments' => CommentResource::collection(Comment::onlyTrashed()->get())
        ]);
    }
}
