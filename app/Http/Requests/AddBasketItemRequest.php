<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddBasketItemRequest extends FormRequest
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
            'quantity' => 'required|integer|min:1',
            'vendor_price' => 'required|integer|min:1000',
            'discount' => 'nullable|numeric|min:0',
            'book_id' => 'required|exists:books,id',
            'fund_id' => 'required|exists:funds,id'
        ];
    }
}
