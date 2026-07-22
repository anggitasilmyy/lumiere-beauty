<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipLevel extends Model
{
    use HasFactory;

    public $timestamps = false;
  
    protected $fillable = [
        'level_name',
        'min_points',
        'benefits',
        'created_at',
    ];

    protected $casts = [
        'min_points' => 'integer',
        'created_at' => 'datetime',
    ];

    // Relasi membership level ke user. 1 : M
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Relasi membership level ke promotion.
    public function promotions()
    {
        return $this->hasMany(Promotion::class, 'minimum_membership_level_id');
    }
}