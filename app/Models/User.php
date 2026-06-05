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
        'google_id',    // Google OAuth ID
        // Profile buyer (semua data sensitif di-encrypt)
        'nickname',     // Nama panggilan
        'phone',        // Nomor telepon
        'address',      // Alamat lengkap
        'city',         // Kota/Kabupaten
        'district',     // Kecamatan
        'avatar_path',  // Path foto profil
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
        ];
    }

    // Cybersecurity: Graceful Decryption Fallback to prevent crash on cleartext legacy data
    public function getPhoneAttribute($value)
    {
        if (empty($value)) return $value;
        try {
            return decrypt($value);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return $value;
        }
    }

    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = empty($value) ? $value : encrypt($value);
    }

    public function getAddressAttribute($value)
    {
        if (empty($value)) return $value;
        try {
            return decrypt($value);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return $value;
        }
    }

    public function setAddressAttribute($value)
    {
        $this->attributes['address'] = empty($value) ? $value : encrypt($value);
    }

    public function getCityAttribute($value)
    {
        if (empty($value)) return $value;
        try {
            return decrypt($value);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return $value;
        }
    }

    public function setCityAttribute($value)
    {
        $this->attributes['city'] = empty($value) ? $value : encrypt($value);
    }

    public function getDistrictAttribute($value)
    {
        if (empty($value)) return $value;
        try {
            return decrypt($value);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return $value;
        }
    }

    public function setDistrictAttribute($value)
    {
        $this->attributes['district'] = empty($value) ? $value : encrypt($value);
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
}
