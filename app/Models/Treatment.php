<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Treatment extends Model
{
    public function promotions()
    {
        return $this->belongsToMany(Promotion::class)->withTimestamps();
    }
    
    use HasFactory;

    protected $fillable = [
        'treatment_name',
        'slug',
        'category',
        'short_description',
        'description',
        'price',
        'duration_minutes',
        'image',
        'is_featured',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'duration_minutes' => 'integer',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Treatment $treatment) {
            if (blank($treatment->slug)) {
                $treatment->slug = Str::slug($treatment->treatment_name);
            }
        });
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }
}