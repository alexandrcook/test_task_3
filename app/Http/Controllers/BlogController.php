<?php

namespace App\Http\Controllers;

use App\Http\Requests\{StoreBlogRequest,UpdateBlogRequest};
use App\Http\Resources\BlogResource;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Blog;
use App\Models\Comment;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BlogController extends Controller
{
    public function index()
    {
        $skip_blogs_count = request()->get('skip_blogs_count');
        $take_blogs_count = 10;

        $allBlogsLoaded = !Blog::query()
            ->skip($skip_blogs_count+$take_blogs_count+1)
            ->take(1)
            ->get()
            ->count();

        return BlogResource::collection(
            Blog::with(['user', 'posts'])
                ->latest()
                ->orderBy('id', 'desc')
                ->skip($skip_blogs_count)
                ->take($take_blogs_count)
                ->get()
        )->additional([
            'allBlogsLoaded' => $allBlogsLoaded
        ]);
    }

    public function store(StoreBlogRequest $request)
    {
        $additionalInputs = ([
            'user_id' => $request->user()->id,
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString()
        ]);

        return new BlogResource(Blog::create(array_merge($request->all(), $additionalInputs)));
    }

    public function show($id)
    {
        $blog = Blog::where('id', $id)
            ->with(['user', 'posts'])
            ->first();

        if(!$blog){
            throw new NotFoundHttpException();
        }

        $skip_posts_count = request()->get('skip_posts_count');
        $take_posts_count = 10;

        $paginatedPosts = Post::where('blog_id', $id)
            ->with('comments')
            ->latest()
            ->orderBy('id', 'desc')
            ->skip($skip_posts_count)
            ->take($take_posts_count)
            ->get();

        $allPostsLoaded = !Post::where('blog_id', $id)
            ->skip($skip_posts_count+$take_posts_count+1)
            ->take(1)
            ->get()
            ->count();

        return (new BlogResource($blog))->additional([
            'postsData' => (new PostCollection($paginatedPosts))->response()->getData(),
            'allPostsLoaded' => $allPostsLoaded
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBlogRequest  $request
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBlogRequest $request, Blog $blog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($blog_id)
    {
        $blog = Blog::onlyTrashed()->where('id', $blog_id)->first();

        if(!$blog){
            throw new NotFoundHttpException();
        }

        $blog->forceDelete();

        return response()->json([
            'removed' => true,
            'trashedBlogs' => BlogResource::collection(Blog::onlyTrashed()->get()),
            'trashedPosts' => PostResource::collection(Post::onlyTrashed()->get()),
            'trashedComments' => CommentResource::collection(Comment::onlyTrashed()->get())
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Blog $blog)
    {
        $blog->delete();
        if($blog->posts){
            $blog->posts->each->delete();
            foreach($blog->posts as $post){
                $post->comments->each->delete(); //todo: probably we could use some listener for cascade softDelete
            }
        }

        return response()->json([
            'removed' => true
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore($blog_id)
    {
        $blog = Blog::onlyTrashed()->where('id', $blog_id)->first();

        if(!$blog){
            throw new NotFoundHttpException();
        }

        $blog->restore();
        foreach($blog->posts()->withTrashed()->get() as $post){
            $post->restore();
            foreach ($post->comments()->withTrashed()->get() as $comment){
                $comment->restore();
            }
        }

        return response()->json([
            'restored' => true
        ]);
    }

    public function getUserBlogs(Request $request)
    {
        return BlogResource::collection(Blog::where('user_id', $request->user()->id)->get());
    }
}
