<?php

namespace App\Http\Requests\Receptionist;

use Illuminate\Foundation\Http\FormRequest;

class WalkInRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && in_array($this->user()->role, ['admin', 'receptionist']);
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:80',
            'last_name' => 'required|string|max:80',
            'phone' => 'required|string|max:30',
            'email' => 'nullable|email|max:150',
            'cnic' => 'nullable|string|max:20',
            'passport_number' => 'nullable|string|max:30',
            'nationality' => 'required|string|max:60',
            'address' => 'nullable|string|max:500',
            'room_id' => 'required|exists:rooms,id',
            'check_out_date' => 'required|date|after:today',
            'num_guests' => 'required|integer|min:1|max:20',
            'notes' => 'nullable|string|max:1000',
        ];
    }
}
