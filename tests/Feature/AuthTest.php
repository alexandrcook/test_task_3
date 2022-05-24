<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_the_user_login()
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
    }
}
