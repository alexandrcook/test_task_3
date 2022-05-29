<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostCommentStoreRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Request;

class PostCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($post_id, $page = '')
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
