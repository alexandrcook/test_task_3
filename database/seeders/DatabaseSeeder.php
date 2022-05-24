<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $users = User::factory(2)->create();

        //todo(refactoring): try to use ->for() method for factory instead foreach()
        foreach ($users as $user){
            $blogs = Blog::factory(2)->create(['user_id' => $user->id]);

            foreach ($blogs as $blog){
                $posts = Post::factory(2)->create(['blog_id' => $blog->id]);

                foreach ($posts as $post){
                    $comments = Comment::factory(2)->create(['user_id' => $user->id, 'post_id' => $post->id]);

                    foreach ($comments as $comment){
                        Comment::factory(2)
                            ->create(['user_id' => $user->id, 'post_id' => $post->id, 'comment_id' => $comment->id]);
                    }
                }
            }
        }
    }
}
