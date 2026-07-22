<?php

namespace App\Models;

// HasFactory digunakan agar model Role bisa memakai factory saat testing atau seeding.
use Illuminate\Database\Eloquent\Factories\HasFactory;

// Model adalah class dasar Eloquent untuk menghubungkan file ini dengan tabel database.
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    // Trait bawaan Laravel untuk mendukung pembuatan data role menggunakan factory.
    use HasFactory;

    // Kolom yang boleh diisi secara massal pada tabel roles.
    // name berisi nama role seperti admin atau customer.
    // label berisi nama tampilan role seperti Administrator atau Customer.
    protected $fillable = [
        'name',
        'label',
    ];

    // Relasi role ke user melalui tabel pivot role_user.
    // Satu role bisa dimiliki oleh banyak user.
    // Contoh: role customer bisa dimiliki oleh banyak akun customer.
    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}