<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\{Comment, Post};
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PostCommentController extends Controller
{
    /**
     * Display a listing of commentaries for specific post.
     */
    public function index(Post $post) : AnonymousResourceCollection
    {
        return CommentResource::collection(
            $post->comments()
                ->with('user')
                ->latest('id')
                ->cursorPaginate()
        );
    }

    /**
     * Display a listing of trashed commentaries.
     */
    public function trashed() : AnonymousResourceCollection
    {
        return CommentResource::collection(
            Comment::onlyTrashed()
                ->with(['post' => fn($q) => $q->with('blog')])
                ->latest('id')
                ->cursorPaginate()
        );
    }

    /**
     * Store a newly created commentary.
     */
    public function store(StorePostCommentRequest $request, Post $post) : CommentResource
    {
        return new CommentResource(
            $post->comments()
                ->create(array_merge(['user_id' => $request->user()->id], $request->all()))
                ->load('user')
        );
    }

    /**
     * Soft delete the specified resource.
     */
    public function delete(Comment $comment) : JsonResponse
    {
        $comment->delete();

        return response()->json(['removed' => true]);
    }

    /**
     * Restore the specified resource.
     */
    public function restore(int $comment_id) : JsonResponse
    {
        $comment = Comment::onlyTrashed()->where('id', $comment_id)->first();

        $comment->restore();

        return response()->json(['restored' => true]);
    }

    /**
     * Destroy the specified resource.
     */
    public function destroy(int $comment_id) : JsonResponse
    {
        $comment = Comment::onlyTrashed()->where('id', $comment_id)->first();

        $comment->forceDelete();

        return response()->json(['removed' => true]);
    }
}
