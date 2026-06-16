<?php

namespace App\Http\Requests\Receptionist;

use Illuminate\Foundation\Http\FormRequest;

class GuestStoreRequest extends FormRequest
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
            'email' => 'nullable|email|max:150',
            'phone' => 'required|string|max:30',
            'cnic' => 'nullable|string|max:20',
            'passport_number' => 'nullable|string|max:30',
            'nationality' => 'required|string|max:60',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:80',
            'country' => 'required|string|max:80',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'emergency_contact_name' => 'nullable|string|max:120',
            'emergency_contact_phone' => 'nullable|string|max:30',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'cnic.required_without' => 'CNIC or Passport Number is required for identification.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'nationality' => $this->nationality ?: 'Pakistani',
            'country' => $this->country ?: 'Pakistan',
        ]);
    }
}
