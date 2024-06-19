<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@example.com',
                'company_name' => 'Acme Inc.',
                'company_address' => '123 Main Street, Anytown USA',
                'phone_number' => '+1 (555) 555-5555',
                'password' => bcrypt('password'),
                'user_role' => 'admin',
                'password_attempt' => 0,
            ]];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
