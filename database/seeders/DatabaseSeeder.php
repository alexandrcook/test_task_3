<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if(!User::where('email','admin@admin.admin')->first()){
            $adminUser = User::factory(1)->create([
                'email' => 'admin@admin.admin',
                'password' => Hash::make('admin'),
                'is_admin' => true
            ]);
            $adminUser->first()->createToken('api_token',['admin']);
        }

        $users = User::factory(2)->create();

        //todo(refactoring): try to use ->for() method for factory instead foreach()
        foreach ($users as $user){
            $blogs = Blog::factory(10)->create(['user_id' => $user->id]);

            foreach ($blogs as $blog){
                $posts = Post::factory(10)->create(['blog_id' => $blog->id]);

                foreach ($posts as $post){
                    Comment::factory(10)->create(['user_id' => $user->id, 'post_id' => $post->id]);
                }
            }
        }
    }
}
