<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoomUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdmin();
    }

    public function rules(): array
    {
        $id = $this->route('room');

        return [
            'room_number' => ['required', 'string', 'max:20', Rule::unique('rooms', 'room_number')->ignore($id)],
            'room_type_id' => 'required|exists:room_types,id',
            'floor' => 'required|integer|min:0|max:50',
            'price_per_night' => 'required|numeric|min:0|max:9999999',
            'status' => 'required|in:available,occupied,reserved,maintenance',
            'notes' => 'nullable|string|max:500',
            'is_active' => 'sometimes|boolean',
        ];
    }
}
