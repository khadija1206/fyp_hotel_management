<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_reference', 'booking_id', 'guest_id',
        'amount', 'method', 'type',
        'payment_date', 'transaction_id', 'notes',
        'received_by', 'voided_at', 'void_reason',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
        'voided_at' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function isVoided(): bool
    {
        return $this->voided_at !== null;
    }

    public function isRefund(): bool
    {
        return $this->type === 'refund';
    }

    public function getMethodLabelAttribute(): string
    {
        return match ($this->method) {
            'cash' => 'Cash',
            'card' => 'Card',
            'bank_transfer' => 'Bank Transfer',
            'mobile_wallet' => 'Mobile Wallet',
            default => ucfirst($this->method),
        };
    }

    public function getMethodIconAttribute(): string
    {
        return match ($this->method) {
            'cash' => 'cash-stack',
            'card' => 'credit-card',
            'bank_transfer' => 'bank',
            'mobile_wallet' => 'phone',
            default => 'currency-exchange',
        };
    }

    public function scopeNotVoided($query)
    {
        return $query->whereNull('voided_at');
    }

    public function scopePayments($query)
    {
        return $query->where('type', 'payment');
    }

    public function scopeRefunds($query)
    {
        return $query->where('type', 'refund');
    }

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('payment_date', $date);
    }
}
