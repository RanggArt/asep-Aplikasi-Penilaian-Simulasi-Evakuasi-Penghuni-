<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => '175254@admin.local'],
            [
                'name' => 'Admin APEM',
                'password' => '123456',
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => '176096@admin.local'],
            [
                'name' => 'Super Admin APEM',
                'password' => '123456',
                'role' => 'super_admin',
            ]
        );
    }
}
