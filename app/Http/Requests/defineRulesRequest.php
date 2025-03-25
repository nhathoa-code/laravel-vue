<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class defineRulesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'current_checkouts_allowed' => 'required|integer|min:1',
            'loan_period' => 'required|integer|min:1',
            'renewals_allowed' => 'required|integer|min:0',
            'total_holds_allowed' => 'required|integer|min:0',
            'fine_amount' => 'required|integer|min:1000',
            'fine_charging_interval' => 'required|integer|min:1',
            'overdue_fines_cap' => 'required|integer|min:1000'
        ];
    }
}
