<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostCommentStoreRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;

class PostCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  int  $post_id
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function index($post_id)
    {
        $comments = Comment::where(['post_id'=> $post_id, 'comment_id' => null ])
            ->with('childComments')
            ->paginate()
            ->withPath("/posts/{$post_id}/comments");

        return new CommentResource($comments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PostCommentStoreRequest $request
     * @param  int $post_id
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function store(PostCommentStoreRequest $request, $post_id)
    {
        $comment = Comment::create(array_merge([
            'post_id' => $post_id,
            'user_id' => $request->user()->id
        ], $request->all()));

        $commentWithUser = Comment::where('id', $comment->id)->with('user')->first();

        return new CommentResource($commentWithUser);
    }
}
