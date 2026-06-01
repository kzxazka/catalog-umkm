<?php

namespace App\Models;

// WAJIB: Pakai model MongoDB, bukan Eloquent standar
use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Store extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'stores';

    protected $fillable = [
        'user_id',       // ID UMKM yang punya toko
        'name',          // Nama Brand
        'slug',          // Buat URL
        'description',   // Tentang UMKM
        'category',      // Kategori Toko (Fashion, Kuliner, dll)
        'logo',          // Path logo toko
        'header_image',  // Foto header/banner toko
        'social_links',  // Array: [ 'instagram' => '...', 'whatsapp' => '...' ]
        // Alamat toko — untuk filter lokasi di katalog publik
        'address',       // Alamat lengkap
        'city',          // Kota/Kabupaten
        'district',      // Kecamatan
        'whatsapp',      // No WA Toko (Enkripsi)
        'instagram',     // Username IG Toko (Enkripsi)
        // Data Sensitif Verifikasi
        'nik',           // NIK Pemilik
        'nib',           // Nomor Induk Berusaha
        'ktp_path',      // Lokasi file KTP
        'nib_path',      // Lokasi file NIB
    ];

    // Cybersecurity: Enkripsi data sensitif di Database
    protected function casts(): array
    {
        return [];
    }

    // Cybersecurity: Graceful Decryption Fallback to prevent crash on cleartext legacy data
    public function getWhatsappAttribute($value)
    {
        if (empty($value)) return $value;
        try {
            return decrypt($value);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return $value;
        }
    }

    public function setWhatsappAttribute($value)
    {
        $this->attributes['whatsapp'] = empty($value) ? $value : encrypt($value);
    }

    public function getInstagramAttribute($value)
    {
        if (empty($value)) return $value;
        try {
            return decrypt($value);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return $value;
        }
    }

    public function setInstagramAttribute($value)
    {
        $this->attributes['instagram'] = empty($value) ? $value : encrypt($value);
    }

    public function getNikAttribute($value)
    {
        if (empty($value)) return $value;
        try {
            return decrypt($value);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return $value;
        }
    }

    public function setNikAttribute($value)
    {
        $this->attributes['nik'] = empty($value) ? $value : encrypt($value);
    }

    public function getNibAttribute($value)
    {
        if (empty($value)) return $value;
        try {
            return decrypt($value);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return $value;
        }
    }

    public function setNibAttribute($value)
    {
        $this->attributes['nib'] = empty($value) ? $value : encrypt($value);
    }

    // Relasi: Satu toko dimiliki oleh satu User (UMKM)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Satu toko punya banyak produk
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}