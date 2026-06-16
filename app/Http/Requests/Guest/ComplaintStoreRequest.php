<?php

namespace App\Http\Requests\Guest;

use Illuminate\Foundation\Http\FormRequest;

class ComplaintStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'guest';
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:200',
            'description' => 'required|string|max:2000',
            'category' => 'required|in:room,food,service,billing,noise,cleanliness,other',
            'booking_id' => 'nullable|exists:bookings,id',
        ];
    }
}
