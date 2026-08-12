<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $hasPromotionId = Schema::hasColumn('bookings', 'promotion_id');
        $hasPromoCode = Schema::hasColumn('bookings', 'promo_code');
        $hasOriginalPrice = Schema::hasColumn('bookings', 'original_price');
        $hasDiscountPercent = Schema::hasColumn('bookings', 'discount_percent');
        $hasDiscountAmount = Schema::hasColumn('bookings', 'discount_amount');

        Schema::table('bookings', function (Blueprint $table) use (
            $hasPromotionId,
            $hasPromoCode,
            $hasOriginalPrice,
            $hasDiscountPercent,
            $hasDiscountAmount
        ) {
            if (!$hasPromotionId) {
                $table->foreignId('promotion_id')
                    ->nullable()
                    ->after('treatment_id')
                    ->constrained('promotions')
                    ->nullOnDelete();
            }

            if (!$hasPromoCode) {
                $table->string('promo_code')
                    ->nullable()
                    ->after('promotion_id');
            }

            if (!$hasOriginalPrice) {
                $table->decimal('original_price', 12, 2)
                    ->nullable()
                    ->after('promo_code');
            }

            if (!$hasDiscountPercent) {
                $table->decimal('discount_percent', 5, 2)
                    ->default(0)
                    ->after('original_price');
            }

            if (!$hasDiscountAmount) {
                $table->decimal('discount_amount', 12, 2)
                    ->default(0)
                    ->after('discount_percent');
            }
        });
    }

    public function down(): void
    {
        // Intentionally left empty.
        //
        // Some existing Lumiere Beauty databases already contained
        // these promotion fields before this migration was created.
        // Keeping down() non-destructive prevents accidental deletion
        // of existing booking data during rollback.
    }
};