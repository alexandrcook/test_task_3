<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBlogPostRequest;
use App\Http\Resources\PostResource;
use App\Models\{Blog, Post};
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BlogPostController extends Controller
{
    /**
     * Display a listing of posts for specific blog.
     */
    public function index(Blog $blog) : AnonymousResourceCollection
    {
        return PostResource::collection(
            $blog->posts()
            ->with('comments')
            ->latest('id')
            ->cursorPaginate()
        );
    }

    /**
     * Display a listing of trashed posts.
     */
    public function trashed() : AnonymousResourceCollection
    {
        return PostResource::collection(
            Post::onlyTrashed()
                ->latest('id')
                ->cursorPaginate()
        );
    }

    /**
     * Store a newly created post for specific blog.
     */
    public function store(StoreBlogPostRequest $request, Blog $blog) : PostResource
    {
        return new PostResource($blog->posts()->create($request->all())->load('comments'));
    }

    /**
     * Display the specified post.
     */
    public function show(Post $post) : PostResource
    {
        return new PostResource($post->load('blog.user'));
    }

    /**
     * Soft delete the specified post.
     */
    public function delete(Post $post) : JsonResponse
    {
        $post->delete();

        return response()->json(['removed' => true]);
    }

    /**
     * Restore the specified post.
     */
    public function restore(int $post_id) : JsonResponse
    {
        $post = post::onlyTrashed()->where('id', $post_id)->first();

        $post->restore();

        return response()->json(['restored' => true]);
    }

    /**
     * Destroy the specified post.
     */
    public function destroy(int $post_id) : JsonResponse
    {
        $post = Post::onlyTrashed()->where('id', $post_id)->first();

        $post->forceDelete();

        return response()->json(['removed' => true]);
    }
}
