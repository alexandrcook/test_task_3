<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserAddress;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthTest extends TestCase
{
//    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_the_user_registered()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->make([
            'password' => '123123'
        ]);

        $response = $this->post(route('api.register'), $user->makeVisible('password')->toArray());

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => ['user_id',  'is_admin', 'api_token']])
            ->assertJsonFragment(
                [
                    'is_admin' => false
                ]
                //check token length
                //check user_id returned
            );

        $this->assertDatabaseHas('users', ['email' => $user->email]);
    }
}
