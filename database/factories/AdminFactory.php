<?php

namespace Database\Factories;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Admin>
 */
class AdminFactory extends Factory
{
    protected $model = Admin::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        return [
            'username' => fake()->unique()->userName,
            'email' => fake()->unique()->safeEmail,
            'password' => fake()->password,
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ];
    }
}
