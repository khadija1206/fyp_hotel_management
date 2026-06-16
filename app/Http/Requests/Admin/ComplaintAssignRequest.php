<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ComplaintAssignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'assigned_to' => 'required|exists:users,id',
            'priority' => 'nullable|in:low,medium,high',
        ];
    }
}
