<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_reference', 'guest_id', 'room_id',
        'check_in_date', 'check_out_date',
        'actual_check_in_at', 'actual_check_out_at',
        'num_nights', 'num_guests',
        'rate_per_night', 'subtotal', 'tax_rate', 'tax_amount', 'total_amount',
        'status', 'payment_status', 'is_walk_in',
        'notes', 'created_by', 'cancelled_at', 'cancellation_reason',
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'actual_check_in_at' => 'datetime',
        'actual_check_out_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'rate_per_night' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'is_walk_in' => 'boolean',
    ];

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class)->whereNull('voided_at');
    }

    public function allPayments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getAmountPaidAttribute(): float
    {
        return (float) $this->payments()
            ->where('type', 'payment')
            ->sum('amount') - $this->payments()->where('type', 'refund')->sum('amount');
    }

    public function getAmountDueAttribute(): float
    {
        return max(0, (float) $this->total_amount - $this->amount_paid);
    }

    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    public function isCheckedIn(): bool
    {
        return $this->status === 'checked_in';
    }

    public function isCheckedOut(): bool
    {
        return $this->status === 'checked_out';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isActive(): bool
    {
        return in_array($this->status, ['confirmed', 'checked_in']);
    }

    public function canBeCheckedIn(): bool
    {
        return $this->status === 'confirmed'
            && ($this->check_in_date->isToday() || $this->check_in_date->isPast());
    }

    public function canBeCheckedOut(): bool
    {
        return $this->status === 'checked_in';
    }

    public function canBeCancelled(): bool
    {
        return $this->status === 'confirmed';
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'confirmed' => 'info',
            'checked_in' => 'success',
            'checked_out' => 'neutral',
            'cancelled' => 'danger',
            'no_show' => 'warning',
            default => 'neutral',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'checked_in' => 'Checked In',
            'checked_out' => 'Checked Out',
            'no_show' => 'No Show',
            default => ucfirst($this->status),
        };
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['confirmed', 'checked_in']);
    }

    public function scopeForToday($query)
    {
        return $query->whereDate('check_in_date', today());
    }

    public function scopeCheckOutToday($query)
    {
        return $query->whereDate('check_out_date', today())->where('status', 'checked_in');
    }

    public function scopeCurrentlyCheckedIn($query)
    {
        return $query->where('status', 'checked_in');
    }
}
