<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointTransaction extends Model
{
    // Trait bawaan Laravel untuk mendukung pembuatan data menggunakan factory.
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'booking_id',
        'points',
        'transaction_type',
        'description',
        'expired_at',
        'created_at',
    ];

    // Mengatur tipe data agar otomatis dibaca sesuai kebutuhan sistem.
    protected $casts = [
        'points' => 'integer',
        'expired_at' => 'date',
        'created_at' => 'datetime',
    ];

    // Relasi transaksi poin ke user. 1 : 1
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi transaksi poin ke booking. Transaksi poin bisa berasal dari satu booking tertentu.
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}