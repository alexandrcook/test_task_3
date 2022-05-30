<?php

namespace Tests\Unit;

use App\Models\Blog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogTest extends TestCase
{
//    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_that_blog_is_created_by_factory()
    {
        $blog = Blog::factory()->create();
        $this->assertTrue($blog instanceof Blog);
    }
}
