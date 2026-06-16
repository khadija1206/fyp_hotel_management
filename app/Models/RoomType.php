<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'capacity', 'bed_count',
        'bed_layout', 'base_price', 'amenities', 'is_active',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'is_active' => 'boolean',
        'capacity' => 'integer',
        'bed_count' => 'integer',
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function getAmenitiesArrayAttribute(): array
    {
        if (!$this->amenities) {
            return [];
        }

        return array_map('trim', explode(',', $this->amenities));
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
