<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class VerifyAccountFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => $this->faker->uuid(),
            'user_type' => $this->faker->randomElement(['Admin', 'User']),
            'token' => Str::random(40),
            'created_at' => Carbon::now(),
        ];
    }
}
