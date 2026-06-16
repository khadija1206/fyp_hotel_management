<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RoomStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'room_number' => 'required|string|max:20|unique:rooms,room_number',
            'room_type_id' => 'required|exists:room_types,id',
            'floor' => 'required|integer|min:0|max:50',
            'price_per_night' => 'required|numeric|min:0|max:9999999',
            'status' => 'required|in:available,occupied,reserved,maintenance',
            'notes' => 'nullable|string|max:500',
            'is_active' => 'sometimes|boolean',
        ];
    }
}
