<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RoomTypeStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:50|unique:room_types,name',
            'description' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:1|max:20',
            'bed_count' => 'required|integer|min:1|max:10',
            'bed_layout' => 'required|in:single,double,twin,suite',
            'base_price' => 'required|numeric|min:0|max:9999999',
            'amenities' => 'nullable|string|max:500',
            'is_active' => 'sometimes|boolean',
        ];
    }
}
