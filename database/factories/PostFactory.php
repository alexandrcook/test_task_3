<?php

namespace Database\Factories;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'blog_id' => function(){
                return Blog::factory()->create(['user_id' => User::factory()->create()->id])->id;
            },
            'subject' => $this->faker->sentence(),
            'body' => $this->faker->text(),
            'created_at' => now(),
            'updated_at' => now()

        ];
    }
}
