<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SettingsUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'hotel_name' => 'required|string|max:150',
            'hotel_address' => 'required|string|max:300',
            'hotel_phone' => 'required|string|max:30',
            'hotel_email' => 'required|email|max:150',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'default_check_in_time' => 'required|date_format:H:i',
            'default_check_out_time' => 'required|date_format:H:i',
            'currency_symbol' => 'required|string|max:10',
        ];
    }
}
