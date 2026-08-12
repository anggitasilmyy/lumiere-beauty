<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'user_id',
        'treatment_id',
        'promotion_id',
        'promo_code',
        'booking_date',
        'booking_time',
        'notes',
        'original_price',
        'discount_percent',
        'discount_amount',
        'total_price',
        'payment_status',
        'booking_status',
        'treatment_status',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'treatment_id' => 'integer',
        'promotion_id' => 'integer',
        'booking_date' => 'date',
        'original_price' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function treatment()
    {
        return $this->belongsTo(Treatment::class);
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function pointTransactions()
    {
        return $this->hasMany(PointTransaction::class);
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }
}
