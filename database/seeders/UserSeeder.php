<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserInformation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (User::query()->count() > 0) {
            return;
        }

        // Create a new UserInformation record
        $userInformation = UserInformation::factory()->create();

        // Create a new User record
        $user = User::factory()->create([
            'username' => 'user',
            'email' => 'user@gmail.com',
            'password' => bcrypt('P@$$word1'),
            'email_verified_at' => now(),
            'user_information_id' => $userInformation->id,
        ]);
    }
}
