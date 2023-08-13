<?php

namespace Database\Seeders;

use App\Models\UserInformation;
use Faker\Factory;
use Illuminate\Database\Seeder;

class UserInformationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (UserInformation::query()->count() > 0) {
            return;
        }
        //        UserInformation::factory()->create();
    }
}
