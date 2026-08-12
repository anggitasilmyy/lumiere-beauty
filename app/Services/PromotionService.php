<?php

namespace App\Services;

use App\Models\Promotion;
use App\Models\Treatment;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PromotionService
{
    public function calculate(User $user, Treatment $treatment, ?string $promoCode): array
    {
        $originalPrice = round((float) $treatment->price, 2);

        if (blank($promoCode)) {
            return $this->normalPrice($originalPrice);
        }

        $normalizedCode = Str::upper(trim($promoCode));

        $promotion = Promotion::with([
            'minimumMembershipLevel',
            'treatments',
        ])
            ->whereRaw('UPPER(promo_code) = ?', [$normalizedCode])
            ->first();

        if (!$promotion) {
            $this->fail('Kode promo tidak ditemukan.');
        }

        if (!$promotion->is_active) {
            $this->fail('Promo sedang tidak aktif.');
        }

        $today = today();

        if ($promotion->start_date && $today->lt($promotion->start_date)) {
            $this->fail('Promo belum dapat digunakan.');
        }

        if ($promotion->end_date && $today->gt($promotion->end_date)) {
            $this->fail('Masa berlaku promo telah berakhir.');
        }

        if (
            $promotion->treatments->isNotEmpty() &&
            !$promotion->treatments->contains('id', $treatment->id)
        ) {
            $this->fail('Promo tidak berlaku untuk treatment yang dipilih.');
        }

        if ($promotion->membership_only) {
            $user->loadMissing('membershipLevel');

            $requiredLevel = $promotion->minimumMembershipLevel;

            if (!$requiredLevel) {
                $this->fail('Pengaturan minimum membership promo belum lengkap.');
            }

            $userMinimumPoints = (int) ($user->membershipLevel?->min_points ?? 0);
            $requiredMinimumPoints = (int) $requiredLevel->min_points;

            if ($userMinimumPoints < $requiredMinimumPoints) {
                $this->fail('Level membership Anda belum mencukupi untuk promo ini.');
            }
        }

        $discountPercent = min(100, max(0, (float) $promotion->discount_percent));
        $discountAmount = round($originalPrice * ($discountPercent / 100), 2);
        $finalPrice = max(0, round($originalPrice - $discountAmount, 2));

        return [
            'promotion_id' => $promotion->id,
            'promotion_title' => $promotion->title,
            'promo_code' => $promotion->promo_code,
            'original_price' => $originalPrice,
            'discount_percent' => $discountPercent,
            'discount_amount' => $discountAmount,
            'final_price' => $finalPrice,
        ];
    }

    private function normalPrice(float $originalPrice): array
    {
        return [
            'promotion_id' => null,
            'promotion_title' => null,
            'promo_code' => null,
            'original_price' => $originalPrice,
            'discount_percent' => 0,
            'discount_amount' => 0,
            'final_price' => $originalPrice,
        ];
    }

    private function fail(string $message): never
    {
        throw ValidationException::withMessages([
            'promo_code' => $message,
        ]);
    }
}
