<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
class RoleSeeder extends Seeder
{
    /**
     *
     * @return void
     */
    public function run(): void
    {
        Role::query()->firstOrCreate(['guard_name' => 'admin-api', 'name' => 'ADMIN']);
        Role::query()->firstOrCreate(['guard_name' => 'admin-api', 'name' => 'QUDRAT_OPERATOR']);
        Role::query()->firstOrCreate(['guard_name' => 'admin-api', 'name' => 'TAHSILI_OPERATOR']);
        Role::query()->firstOrCreate(['guard_name' => 'admin-api', 'name' => 'STEP_OPERATOR']);
    }
}
