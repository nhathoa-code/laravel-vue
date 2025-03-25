<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class TransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'payment_type' => 'required|in:CASH,CC',
            'cash_register' => 'required|exists:cash_registers,id',
            'amount_tendered' => 'required|integer|min:1000',
            'transaction_items' => 'required|array',
            'transaction_items.*.debit_type' => 'required|string',
            'transaction_items.*.quantity' => 'required|integer|min:1',
            'transaction_items.*.price' => 'required|integer|min:1000'
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $validated = $this->validated();
                $total = 0;
                foreach($validated['transaction_items'] as $item){
                    $total += $item['quantity'] * $item['price'];
                }
                if($validated['amount_tendered'] < $total){
                    $validator->errors()->add("message", "Amount tendered can not less than " . $total);
                }
            }
        ];
    }
}
