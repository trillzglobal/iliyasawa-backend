<?php

namespace Database\Seeders;

use App\Models\UserRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userRoles = [
            [
                'user_id' => 1,
                'role_id' => 1,
            ]];

        foreach ($userRoles as $userRole) {
            UserRole::create($userRole);
        }
    }
}
