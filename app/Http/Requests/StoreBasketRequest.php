<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBasketRequest extends FormRequest
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
        if($this->query('updateStatus') == true){
            return ['status' => 'required|string|in:closed,spending'];
        }
        $rules = [
            'name' => 'required',
            'billing_place' => 'required|exists:libraries,id',
            'notes' => 'nullable|string'
        ];
        if($this->isMethod('POST')){
            $rules['created_by'] = 'required|exists:users,id';
        }
        return $rules;
    }
}
