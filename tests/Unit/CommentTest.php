<?php

namespace Tests\Unit;

use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_that_comment_is_created_by_factory()
    {
        $blog = Comment::factory()->create();
        $this->assertTrue($blog instanceof Comment);
    }
}
