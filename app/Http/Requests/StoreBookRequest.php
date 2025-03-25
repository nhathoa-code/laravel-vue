<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookRequest extends FormRequest
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
            'title' => 'bail|required|string|max:255',
            'author' => 'bail|required|string',
            'publisher' => 'bail|required|string',
            'publish_date' => 'bail|required|digits:4|integer|between:1900,2100',
            'pages' => 'required|integer|min:1',
            'description' => 'required|string',
            'categories' => 'nullable|array|exists:categories,id'
        ];
        if($this->isMethod('PUT')){
            $rules['cover_image'] = 'bail|nullable|image|mimes:jpg,png,webp';
            $rules['isbn'] = ['bail','required','regex:/^(?:\d{10}|\d{13})$/',Rule::unique('books')->ignore($this->route('book')->id)];
        }else{
            $rules['isbn'] = ['bail','required','regex:/^(?:\d{10}|\d{13})$/','unique:books'];
        }
        return $rules;
    }
}
