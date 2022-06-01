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
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_the_user_can_register_and_login()
    {
        $generatedPassword= bin2hex(openssl_random_pseudo_bytes(4));

        $user = User::factory()->make(['password' => $generatedPassword]);

        $registeredResponse = $this->post(route('api.register'), $user->makeVisible('password')->toArray());

        $registeredResponse->assertStatus(200)
            ->assertJsonStructure(['data' => ['user_id',  'is_admin', 'api_token']]);

        $registeredResponseData = $registeredResponse->getOriginalContent()['data'];

        $this->assertDatabaseHas('users', [
            'id' => $registeredResponseData['user_id'],
            'email' => $user->email
        ]);

        $registeredUser = User::where('id', $registeredResponseData['user_id'])->first();

        $loggedResponse = $this->post(route('api.login'), [
            'email' => $registeredUser->email,
            'password' => $generatedPassword
        ]);

        $loggedResponse->assertStatus(200)
            ->assertJsonStructure(['data' => ['user_id',  'is_admin', 'api_token']]);

        $loggedResponseData = $loggedResponse->getOriginalContent()['data'];

        foreach ([$registeredResponseData, $loggedResponseData] as $data){
            $this->assertTrue(is_integer($data['user_id']));
            $this->assertTrue(is_bool($data['is_admin']));
            $this->assertTrue(is_string($data['api_token']));
        }
    }
}
