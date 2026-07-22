<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::updateOrCreate(
            ['name' => 'admin'],
            ['label' => 'Administrator']
        );

        $customerRole = Role::updateOrCreate(
            ['name' => 'customer'],
            ['label' => 'Customer']
        );

        User::where('role', 'admin')->get()->each(function ($user) use ($adminRole) {
            $user->roles()->syncWithoutDetaching([$adminRole->id]);
        });

        User::where('role', 'customer')->get()->each(function ($user) use ($customerRole) {
            $user->roles()->syncWithoutDetaching([$customerRole->id]);
        });
    }
}