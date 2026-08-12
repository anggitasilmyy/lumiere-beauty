<?php

namespace Database\Seeders;

use App\Models\MembershipLevel;
use App\Models\Promotion;
use Illuminate\Database\Seeder;

class PromotionSeeder extends Seeder
{
    public function run(): void
    {
        $bronze = MembershipLevel::where('level_name', 'Bronze')->first();
        $silver = MembershipLevel::where('level_name', 'Silver')->first();
        $gold = MembershipLevel::where('level_name', 'Gold')->first();

        $promotions = [
            [
                'title' => 'First Visit Glow Promo',
                'description' => 'Promo umum untuk customer baru yang ingin mencoba treatment pertama di Lumiere Beauty.',
                'promo_code' => 'FIRSTGLOW',
                'discount_percent' => 10,
                'membership_only' => false,
                'minimum_membership_level_id' => null,
                'start_date' => now()->toDateString(),
                'end_date' => now()->addDays(30)->toDateString(),
                'is_active' => true,
            ],
            [
                'title' => 'Member Facial Booster',
                'description' => 'Promo khusus member aktif Lumiere Beauty untuk treatment facial pilihan.',
                'promo_code' => 'MEMBERFACIAL',
                'discount_percent' => 15,
                'membership_only' => true,
                'minimum_membership_level_id' => $bronze?->id,
                'start_date' => now()->toDateString(),
                'end_date' => now()->addDays(45)->toDateString(),
                'is_active' => true,
            ],
            [
                'title' => 'Silver Skin Reward',
                'description' => 'Promo khusus member Silver ke atas untuk treatment brightening.',
                'promo_code' => 'SILVERGLOW',
                'discount_percent' => 20,
                'membership_only' => true,
                'minimum_membership_level_id' => $silver?->id,
                'start_date' => now()->toDateString(),
                'end_date' => now()->addDays(45)->toDateString(),
                'is_active' => true,
            ],
            [
                'title' => 'Gold Exclusive Treatment',
                'description' => 'Promo eksklusif untuk Gold dan Platinum member.',
                'promo_code' => 'GOLDCARE',
                'discount_percent' => 25,
                'membership_only' => true,
                'minimum_membership_level_id' => $gold?->id,
                'start_date' => now()->toDateString(),
                'end_date' => now()->addDays(60)->toDateString(),
                'is_active' => true,
            ],
        ];

        foreach ($promotions as $promotion) {
            Promotion::updateOrCreate(
                ['promo_code' => $promotion['promo_code']],
                $promotion
            );
        }
    }
}