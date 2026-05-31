<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ChatMessage extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'chat_messages';

    protected $fillable = [
        'store_id',     // ID toko (room identifier)
        'sender_id',    // ID pengirim (buyer atau owner)
        'sender_role',  // 'buyer' | 'owner'
        'message',      // Isi pesan
        'is_read',      // Sudah dibaca owner?
        'product_id',   // (opsional) terkait produk tertentu
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
        ];
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id', '_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', '_id');
    }

    // Scope: pesan untuk room toko tertentu antara owner dan buyer
    public function scopeRoom($query, $storeId, $buyerId)
    {
        return $query->where('store_id', $storeId)
                     ->where(function ($q) use ($buyerId) {
                         $q->where('sender_id', $buyerId)
                           ->orWhere(function ($q2) use ($buyerId) {
                               $q2->where('sender_role', 'owner');
                           });
                     });
    }
}
