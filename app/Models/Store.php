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
        'user_id',     // ID UMKM yang punya toko
        'name',        // Nama Brand (misal: Jagatboemi)
        'slug',        // Buat URL (misal: jagat-boemi)
        'description', // Sejarah/Tentang UMKM
        'logo',        // Path logo toko
        'social_links',// Array: [ 'ig' => '...', 'wa' => '...' ]
        
        // Data Sensitif Verifikasi
        'nik',         // NIK Pemilik
        'nib',         // Nomor Induk Berusaha
        'ktp_path',    // Lokasi file KTP (Private Storage)
        'nib_path',    // Lokasi file NIB (Private Storage)
    ];

    // Cybersecurity: Enkripsi data sensitif di Database
    protected function casts(): array
    {
        return [
            'nik' => 'encrypted',
            'nib' => 'encrypted',
        ];
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