<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\BlogResource;
use App\Http\Resources\CommentCollection;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PaginatedCommentResource;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Blog;
use App\Models\Comment;
use App\Models\Post;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function index()
    {
        $skip_posts_count = request()->get('skip_posts_count');
        $take_posts_count = 10;

        $allPostsLoaded = !Post::query()
            ->skip($skip_posts_count+$take_posts_count+1)
            ->take(1)
            ->get()
            ->count();

        return PostResource::collection(
            Post::with(['blog.user', 'comments'])
                ->latest()
                ->orderBy('id', 'desc')
                ->skip($skip_posts_count)
                ->take($take_posts_count)
                ->get()
        )->additional([
            'allPostsLoaded' => $allPostsLoaded
        ]);
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
     * @param  \App\Http\Requests\StorePostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request)
    {
        return new PostResource(Post::create($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function show($id)
    {
        $skip_comments_count = request()->get('skip_comments');
        $take_comments_count = 10;

        $commentsChunk = Comment::where('post_id', $id)
            ->with('user')
            ->latest()
            ->orderBy('id', 'desc')
            ->skip($skip_comments_count)
            ->take($take_comments_count)
            ->get();

        $allCommentsLoaded = !Comment::where('post_id', $id)
            ->skip($skip_comments_count+$take_comments_count+1)
            ->take(1)->get()->count();

        return (new PostResource(
            Post::where('id',$id)
            ->with(['blog' => function($q){
                return $q->with('user');
            }])
            ->first()
        ))->additional([
            'commentsData' => (new CommentCollection($commentsChunk))->response()->getData(),
            'allCommentsLoaded' => $allCommentsLoaded
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePostRequest  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($post_id)
    {
        $post = Post::onlyTrashed()->where('id', $post_id)->first();

        $post->forceDelete();

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
    public function delete(Post $post)
    {
        $post->delete();
        if($post->comments){
            $post->comments->each->delete();
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
    public function restore($post_id)
    {
        $post = post::onlyTrashed()->where('id', $post_id)->first();

        $post->restore();
        foreach($post->comments()->withTrashed()->get() as $comment){
            $comment->restore();
        }

        return response()->json([
            'restored' => true
        ]);
    }
}
