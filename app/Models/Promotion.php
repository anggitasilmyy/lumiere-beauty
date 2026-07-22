<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Treatment;

class Promotion extends Model
{
    public function treatments()
    {
        return $this->belongsToMany(Treatment::class)->withTimestamps();
    }

    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'promo_code',
        'discount_percent',
        'membership_only',
        'minimum_membership_level_id',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'discount_percent' => 'decimal:2',
        'membership_only' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function minimumMembershipLevel()
    {
        return $this->belongsTo(MembershipLevel::class, 'minimum_membership_level_id');
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }
}