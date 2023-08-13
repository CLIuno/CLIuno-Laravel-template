<?php

namespace Database\Factories;

use App\Models\UserInformation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserInformation>
 */
class UserInformationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'first_name' => 'Abdulqudos',
            'last_name' => 'Ismaail',
            'mobile' => '+966565824926',
            'gender' => $this->faker->randomElement(['male', 'female']),
            'bio' => $this->faker->text(),
            'image' => $this->faker->imageUrl(),
            'is_available' => $this->faker->boolean(),
            'date_of_birth' => $this->faker->date(),
        ];
    }
}
