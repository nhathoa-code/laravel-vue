<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddFundRequest extends FormRequest
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
        $rules = [
            'name' => 'required|string',
            'amount' => 'required|integer|min:100000',
            'warning_at' => 'nullable|integer|lt:amount',
            'users' => 'nullable|array',
            'users.*' => 'integer|exists:users,id',
            'library' => 'nullable|exists:libraries,id',
            'permission' => 'required|integer|in:0,1,2,3',
            'notes' => 'nullable|string',
            'owner' => 'required|exists:users,id'
        ];
        if($this->isMethod('PUT')){
            $rules['code'] = ['required',Rule::unique('funds')->ignore($this->route('fundId'))];
        }else{
            $rules['code'] = 'required|string|max:50|unique:funds';
        }
        return $rules;
    }
}
