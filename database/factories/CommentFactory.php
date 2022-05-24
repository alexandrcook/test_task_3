<?php

namespace Database\Factories;

use App\Models\Blog;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => function(){
                return User::factory()->create()->id;
            },
            'post_id' => function ($attributes) {
                return Post::factory()->create([
                    'blog_id' => Blog::factory()->create([
                            'user_id' => $attributes['user_id']
                        ])
                ])->id;
            },
            'comment_id' => null,
            'message' => $this->faker->sentence(),
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
