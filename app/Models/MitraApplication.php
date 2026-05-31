<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class MitraApplication extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'mitra_applications';

    protected $fillable = [
        'user_id',          // ID buyer yang daftar
        'business_name',    // Nama usaha
        'business_category',// Kategori usaha
        'business_address', // Alamat lengkap usaha
        'city',             // Kota/Kabupaten
        'district',         // Kecamatan
        'description',      // Deskripsi usaha
        'ktp_path',         // Foto KTP (private storage)
        'nib_path',         // Foto NIB/SIUP
        'sertifikat_path',  // Sertifikat usaha lainnya
        'whatsapp',         // No WA usaha
        'instagram',        // IG usaha
        'status',           // pending | approved | rejected
        'reviewed_by',      // ID admin yang review
        'reviewed_at',      // Tanggal review
        'rejection_reason', // Alasan penolakan
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
        ];
    }

    // Relasi ke User (buyer)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', '_id');
    }

    // Scope: status pending
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Scope: status approved
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}
