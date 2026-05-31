<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ProductInquiry extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'product_inquiries';

    protected $fillable = [
        'product_id',   // ID produk
        'store_id',     // ID toko
        'buyer_id',     // ID buyer yang bertanya
        'question',     // Pertanyaan buyer
        'template_key', // template yang digunakan (opsional)
        'reply',        // Jawaban dari owner
        'replied_at',   // Kapan dibalas
        'replied_by',   // ID owner yang membalas
        'is_public',    // Apakah Q&A ini ditampilkan ke publik
    ];

    protected function casts(): array
    {
        return [
            'replied_at' => 'datetime',
            'is_public'  => 'boolean',
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', '_id');
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id', '_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', '_id');
    }
}
