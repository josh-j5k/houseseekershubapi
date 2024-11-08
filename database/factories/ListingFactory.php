<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Listing>
 */
class ListingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $propArr = ['rent', 'sale'];
        $prop_type = ['room', 'studio', 'appartment', 'duplex'];
        $prop_type_num = rand(0, 3);
        $number = rand(0, 1);
        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(),
            'property_status' => $propArr[$number],
            'property_type' => $prop_type[$prop_type_num],
            'location' => fake()->address(),
            'price' => fake()->numberBetween(100000, 1000000),
            'description' => fake()->paragraph(5)
        ];
    }
}
