<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@lumiere.com'],
            [
                'name' => 'Admin Lumiere',
                'phone' => '081234567890',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'membership_level_id' => 1,
                'total_points' => 0,
                'is_active' => true,
            ]
        );

        $adminRole = Role::where('name', 'admin')->first();

        if ($adminRole) {
            $admin->roles()->syncWithoutDetaching([$adminRole->id]);
        }
    }
}