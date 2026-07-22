<?php

namespace App\Models;

// HasFactory digunakan agar model bisa memakai factory saat testing/seeding.
use Illuminate\Database\Eloquent\Factories\HasFactory;

// Authenticatable digunakan karena model User dipakai untuk proses login Laravel.
use Illuminate\Foundation\Auth\User as Authenticatable;

// Notifiable digunakan agar user bisa menerima notifikasi jika dibutuhkan.
use Illuminate\Notifications\Notifiable;

// HasApiTokens digunakan untuk fitur token API dari Laravel Sanctum.
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    // Trait bawaan Laravel untuk API token, factory, dan notifikasi.
    use HasApiTokens, HasFactory, Notifiable;

    // Kolom yang boleh diisi secara massal saat membuat atau mengubah data user.
    // Data ini mendukung fitur akun, role, membership, dan points.
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'membership_level_id',
        'total_points',
        'is_active',
    ];

    // Data sensitif yang disembunyikan agar tidak tampil sembarangan.
    // Password dan remember_token tidak akan muncul saat data user ditampilkan sebagai array/JSON.
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Mengatur tipe data agar otomatis dibaca sesuai kebutuhan sistem.
    protected $casts = [
        'email_verified_at' => 'datetime',

        // Password akan dipastikan tersimpan dalam bentuk hash.
        'password' => 'hashed',

        // is_active dibaca sebagai boolean true/false.
        'is_active' => 'boolean',

        // total_points dibaca sebagai angka integer.
        'total_points' => 'integer',
    ];

    // Relasi user ke membership level.
    // Satu user memiliki satu level membership, misalnya Bronze, Silver, Gold, atau Platinum.
    public function membershipLevel()
    {
        return $this->belongsTo(MembershipLevel::class);
    }

    // Relasi user ke booking.
    // Satu user bisa memiliki banyak booking treatment.
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // Relasi user ke transaksi poin.
    // Satu user bisa memiliki banyak riwayat poin.
    public function pointTransactions()
    {
        return $this->hasMany(PointTransaction::class);
    }

    // Relasi user ke role melalui tabel pivot role_user.
    // Digunakan untuk membedakan akses admin dan customer.
    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    // Mengecek apakah user memiliki role tertentu, misalnya admin atau customer.
    public function hasRole(string $role): bool
    {
        // Jika relasi roles sudah dimuat, cek role langsung dari data yang sudah ada.
        // Ini lebih efisien karena tidak perlu query ulang ke database.
        if ($this->relationLoaded('roles') && $this->roles->contains('name', $role)) {
            return true;
        }

        // Jika relasi roles belum dimuat, cek role langsung ke tabel roles melalui relasi.
        if ($this->roles()->where('name', $role)->exists()) {
            return true;
        }

        // Fallback: cek kolom role langsung di tabel users.
        // Ini berguna jika role masih disimpan di kolom users.role.
        return $this->role === $role;
    }

    // Mengecek apakah user adalah admin.
    // Method ini dipakai untuk mengarahkan user ke dashboard admin.
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    // Mengecek apakah user adalah customer.
    // Method ini dipakai untuk membedakan user biasa/customer.
    public function isCustomer(): bool
    {
        return $this->hasRole('customer');
    }
}