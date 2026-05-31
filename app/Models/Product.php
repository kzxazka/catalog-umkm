<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'products';

    protected $fillable = [
        'store_id',    // Foreign key ke koleksi stores
        'name',
        'category',    // 
        'description',
        'price',
        'images',      // Array path foto 
        'links'        // Array dinamis: [ ['platform' => 'Shopee', 'url' => '...'], ... ]
    ];

    // Relasi balik: Produk ini milik toko mana?
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', '_id');
    }
}