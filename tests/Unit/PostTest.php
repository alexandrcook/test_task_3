<?php

namespace Tests\Unit;

use App\Models\Blog;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_that_post_is_created_by_factory()
    {
        $blog = Post::factory()->create();
        $this->assertTrue($blog instanceof Post);
    }
}
