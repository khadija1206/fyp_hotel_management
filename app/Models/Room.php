<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_number', 'room_type_id', 'floor', 'price_per_night',
        'status', 'notes', 'position_x', 'position_y', 'width', 'height',
        'is_active',
    ];

    protected $casts = [
        'price_per_night' => 'decimal:2',
        'is_active' => 'boolean',
        'floor' => 'integer',
        'position_x' => 'integer',
        'position_y' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
    ];

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function currentBooking()
    {
        return $this->hasOne(Booking::class)->where('status', 'checked_in')->latest();
    }

    public function activeBooking()
    {
        return $this->hasOne(Booking::class)->whereIn('status', ['confirmed', 'checked_in'])->latest();
    }

    public function isAvailableForDates($checkIn, $checkOut, ?int $excludeBookingId = null): bool
    {
        if ($this->status === 'maintenance' || ! $this->is_active) {
            return false;
        }

        $checkIn = \Carbon\Carbon::parse($checkIn);
        $checkOut = \Carbon\Carbon::parse($checkOut);

        $conflict = $this->bookings()
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->when($excludeBookingId, fn ($q) => $q->where('id', '!=', $excludeBookingId))
            ->where(function ($q) use ($checkIn, $checkOut) {
                $q->where('check_in_date', '<', $checkOut)
                    ->where('check_out_date', '>', $checkIn);
            })
            ->exists();

        return ! $conflict;
    }

    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    public function isOccupied(): bool
    {
        return $this->status === 'occupied';
    }

    public function isReserved(): bool
    {
        return $this->status === 'reserved';
    }

    public function isMaintenance(): bool
    {
        return $this->status === 'maintenance';
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'available' => 'success',
            'occupied' => 'danger',
            'reserved' => 'warning',
            'maintenance' => 'neutral',
            default => 'neutral',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return ucfirst($this->status);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeOccupied($query)
    {
        return $query->where('status', 'occupied');
    }

    public function scopeOnFloor($query, int $floor)
    {
        return $query->where('floor', $floor);
    }
}
