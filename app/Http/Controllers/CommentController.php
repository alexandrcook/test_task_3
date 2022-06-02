<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostCommentStoreRequest;
use App\Http\Resources\BlogResource;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Models\Blog;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Destroy the specified resource.
     *
     * @param  int  $comment_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($comment_id)
    {
        $comment = Comment::onlyTrashed()->where('id', $comment_id)->first();

        $comment->forceDelete();

        return response()->json([
            'removed' => true
        ]);
    }

    /**
     * Soft delete the specified resource.
     *
     * @param  int  $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Comment $comment)
    {
        $comment->delete();

        return response()->json([
            'removed' => true
        ]);
    }

    /**
     * Restore the specified resource.
     *
     * @param  int  $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore($comment_id)
    {
        $comment = Comment::onlyTrashed()->where('id', $comment_id)->first();
        $comment->restore();

        return response()->json([
            'restored' => true
        ]);
    }
}
