<?php

namespace Tests\Unit;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
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

    public function test_user_could_create_blog()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(User::factory()->create(), ["*"]);

        $response = $this->postJson(
            route('blogs.store'),
            ['name' => 'test blog name']
        );

        $response->assertStatus(201)
            ->assertJsonStructure(['data' => ['id', 'name', 'created_at', 'updated_at']]);

        $createdBlog = Blog::where([
            'id' => $response['data']['id'],
            'name' => $response['data']['name'],
            ])->first();

//        dump($response['data']['created_at']);
//        dd($createdBlog->created_at);

        $this->assertDatabaseHas('blogs', [
            'id' => $response['data']['id'],
            'name' => $response['data']['name'],
//            'created_at' => $response['data']['created_at'],
//            'updated_at' => $response['data']['updated_at']
        ]);

        $this->assertTrue(is_integer($response['data']['id']));
        $this->assertTrue(is_string($response['data']['name']));
//        $this->assertTrue($response['data']['created_at']);
//        $this->assertTrue($response['data']['updated_at']);
    }
}
