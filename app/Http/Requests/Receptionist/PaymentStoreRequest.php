<?php

namespace App\Http\Requests\Receptionist;

use Illuminate\Foundation\Http\FormRequest;

class PaymentStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && in_array($this->user()->role, ['admin', 'receptionist']);
    }

    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:0.01|max:99999999',
            'method' => 'required|in:cash,card,bank_transfer,mobile_wallet',
            'payment_date' => 'required|date|before_or_equal:today',
            'transaction_id' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:1000',
        ];
    }
}
