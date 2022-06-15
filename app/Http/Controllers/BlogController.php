<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Requests\StoreBlogRequest;
use App\Http\Resources\BlogResource;
use App\Models\Blog;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BlogController extends Controller
{
    /**
     * Display a listing of blogs.
     */
    public function index() : AnonymousResourceCollection
    {
        return BlogResource::collection(
                Blog::with(['user', 'posts'])
                ->latest('id')
                ->cursorPaginate()
        );
    }

    /**
     * Display a listing of the trashed blogs.
     */
    public function trashed() : AnonymousResourceCollection
    {
        return BlogResource::collection(
            Blog::onlyTrashed()
                ->latest('id')
                ->cursorPaginate()
        );
    }

    /**
     * Store a newly created blog.
     */
    public function store(StoreBlogRequest $request) : BlogResource
    {
        return new BlogResource(
            Blog::create(
                array_merge(
                    $request->all(),
                    ['user_id' => $request->user()->id])
            )->load('user')
        );
    }

    /**
     * Display the specified blog.
     */
    public function show(Blog $blog) : BlogResource
    {
        return new BlogResource($blog->load('user'));
    }

    /**
     * Soft delete the specified blog.
     */
    public function delete(Blog $blog) : JsonResponse
    {
        $blog->delete();

        return response()->json(['removed' => true]);
    }

    /**
     * Restore the specified soft deleted blog.
     */
    public function restore(int $blog_id) : JsonResponse
    {
        $blog = Blog::onlyTrashed()->where('id', $blog_id)->first();

        if(!$blog){
            throw new NotFoundHttpException();
        }

        $blog->restore();

        return response()->json(['restored' => true]);
    }

    /**
     * Destroy the specified blog.
     */
    public function destroy($blog_id) : JsonResponse
    {
        $blog = Blog::onlyTrashed()->where('id', $blog_id)->first();

        if(!$blog){
            throw new NotFoundHttpException();
        }

        $blog->forceDelete();

        return response()->json(['removed' => true]);
    }
}
