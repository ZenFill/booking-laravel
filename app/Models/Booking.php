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

    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CANCELLED = 'cancelled';

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: Filter booking berdasarkan role user.
     * Jika admin -> Tampilkan semua (opsional filter user_id).
     * Jika user biasa -> Tampilkan hanya milik sendiri.
     */
    public function scopeForUser($query, $user)
    {
        if ($user->isAdmin()) {
            return $query; // Admin bisa lihat semua
        }

        return $query->where('user_id', $user->id);
    }

    /**
     * Accessor: Warna status untuk kalender
     */
    public function getColorAttribute()
    {
        return match ($this->status) {
            self::STATUS_CONFIRMED => '#10B981', // Hijau
            'approved' => '#10B981', // Backward compatibility

            self::STATUS_CANCELLED => '#EF4444', // Merah
            'rejected' => '#EF4444', // Backward compatibility

            default => '#F59E0B', // Kuning (Pending)
        };
    }
}
