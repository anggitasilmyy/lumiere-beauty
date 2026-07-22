<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            MembershipLevelSeeder::class,
            RoleSeeder::class,
            AdminUserSeeder::class,
            TreatmentSeeder::class,
            PromotionSeeder::class,
        ]);
    }
}