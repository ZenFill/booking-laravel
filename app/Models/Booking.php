<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'service_type',
        'booking_date',
        'notes',
        'status',
        'payment_proof',
    ];

    // Relasi ke User (Opsional tapi penting untuk nanti)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
