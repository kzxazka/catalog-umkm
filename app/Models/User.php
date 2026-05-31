<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $connection = 'mongodb';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',         // admin | owner | buyer
        // Profile buyer (semua data sensitif di-encrypt)
        'nickname',     // Nama panggilan
        'phone',        // Nomor telepon
        'address',      // Alamat lengkap
        'city',         // Kota/Kabupaten
        'district',     // Kecamatan
        'avatar_path',  // Path foto profil
        'favorited_products', // Array product IDs yang difavoritkan
        'followed_stores',    // Array store IDs yang di-follow
    ];

    protected $hidden = ['password', 'remember_token'];

    /**
     * Data sensitif di-encrypt di database.
     * Semua field ini akan otomatis encrypt saat disimpan dan decrypt saat dibaca.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'phone' => 'encrypted',   // ✅ Encrypted
            'address' => 'encrypted',   // ✅ Encrypted
            'city' => 'encrypted',   // ✅ Encrypted
            'district' => 'encrypted',   // ✅ Encrypted
            'favorited_products' => 'array',
            'followed_stores'    => 'array',
        ];
    }

    // Relasi: Satu User (UMKM Owner) punya satu toko
    public function store()
    {
        return $this->hasOne(Store::class, 'user_id', '_id');
    }

    // Relasi: Buyer punya banyak pengajuan mitra
    public function mitraApplications()
    {
        return $this->hasMany(MitraApplication::class, 'user_id', '_id');
    }

    // Helper: apakah buyer sudah mengajukan mitra?
    public function hasPendingMitraApplication(): bool
    {
        return $this->mitraApplications()->where('status', 'pending')->exists();
    }

    // Helper: apakah buyer sudah jadi mitra?
    public function isApprovedMitra(): bool
    {
        return $this->mitraApplications()->where('status', 'approved')->exists();
    }

    // Helper: inisial nama untuk avatar
    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);
        $initials = '';
        foreach (array_slice($words, 0, 2) as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        return $initials;
    }

    // Helper: apakah produk ini sudah difavoritkan?
    public function isFavorited(string $productId): bool
    {
        $favs = $this->favorited_products ?? [];
        return in_array($productId, $favs);
    }

    // Helper: apakah sudah follow toko ini?
    public function isFollowing(string $storeId): bool
    {
        $follows = $this->followed_stores ?? [];
        return in_array($storeId, $follows);
    }
}
