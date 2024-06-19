<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
          ['role'=>'admin'],
          ['role'=>'managers'],
          ['role'=>'store_keeper'],
          ['role'=>'others'],
        ];
        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
