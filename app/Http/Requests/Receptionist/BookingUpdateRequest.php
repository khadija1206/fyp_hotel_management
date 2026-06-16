<?php

namespace App\Http\Requests\Receptionist;

use Illuminate\Foundation\Http\FormRequest;

class BookingUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && in_array($this->user()->role, ['admin', 'receptionist']);
    }

    public function rules(): array
    {
        return [
            'num_guests' => 'required|integer|min:1|max:20',
            'notes' => 'nullable|string|max:1000',
        ];
    }
}
