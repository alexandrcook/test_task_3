<?php

namespace Database\Seeders;

use App\Models\{Blog,Comment,Post,User};
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
                $posts = Post::factory(20)->create(['blog_id' => $blog->id]);

                foreach ($posts as $post){
                    Comment::factory(20)->create(['user_id' => $user->id, 'post_id' => $post->id]);
                }
            }
        }
    }
}
