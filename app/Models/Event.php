<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'events';

    protected $fillable = [
        'title',
        'description',
        'image',         // Path file foto yang di-hash
        'event_date',    // Tanggal event diadakan
        'location',      // Lokasi event
        'status',        // 'active' atau 'draft'
    ];
}
