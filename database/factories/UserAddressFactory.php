<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserAddress>
 */
class UserAddressFactory extends Factory
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
            'address' => $this->faker->address(),
            'city' => $this->faker->city(),
            'state' => $this->faker->citySuffix(),
            'zipcode' => $this->faker->postcode(),
            'created_at' => 111,
            'updated_at' => now()
        ];
    }
}
