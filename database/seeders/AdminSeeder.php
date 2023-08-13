<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\UserInformation;
use Illuminate\Database\Seeder;
class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Admin::query()->count() > 0) {
            return;
        }
        // Create a new UserInformation record
        $userInformation = UserInformation::factory()->create();

        $admin = Admin::factory()->create([
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('P@$$word1'),
            'is_active' => true,
            'email_verified_at' => now(),
            'user_information_id' => $userInformation->id,
        ]);

        $admin->first()->assignRole('ADMIN');

        $userInformation = UserInformation::factory()->create();
        $qudrat_operation = Admin::factory()->create([
            'username' => 'qudratOperator',
            'email' => 'qudratOperator@gmail.com',
            'password' => bcrypt('P@$$word1'),
            'is_active' => true,
            'email_verified_at' => now(),
            'user_information_id' => $userInformation->id,
        ]);
        $qudrat_operation->assignRole('QUDRAT_OPERATOR');

        $userInformation = UserInformation::factory()->create();
        $tahsili_operation = Admin::factory()->create([
            'username' => 'tahsiliOperator',
            'email' => 'tahsiliOperator@gmail.com',
            'password' => bcrypt('P@$$word1'),
            'is_active' => true,
            'email_verified_at' => now(),
            'user_information_id' => $userInformation->id,
        ]);
        $tahsili_operation->assignRole('TAHSILI_OPERATOR');

        $userInformation = UserInformation::factory()->create();
        $step_operation = Admin::factory()->create([
            'username' => 'stepOperator',
            'email' => 'stepOperator@gmail.com',
            'password' => bcrypt('P@$$word1'),
            'is_active' => true,
            'email_verified_at' => now(),
            'user_information_id' => $userInformation->id,
        ]);
        $step_operation->assignRole('STEP_OPERATOR');
    }

}
