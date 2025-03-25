<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
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
            'name' => 'bail|required|string',
            'role' => 'bail|required|string|in:admin,staff,patron'
        ];

        $meta_rules = [
            'phone' => ['bail','required','string'],
            'birthdate' => 'bail|required|date',
            'gender' => 'bail|required|string|in:male,female',
            'address' => 'bail|required|string',
            'library' => 'bail|nullable|exists:libraries,id',
        ];

        $user = $this->route('user');

        if($this->isMethod('PUT')){
            $rules['username'] = ['bail','required','string',Rule::unique('users')->ignore($user->id)];
            $rules['email'] = ['bail','required','email',Rule::unique('users')->ignore($user->id)];
            $rules['password'] = 'bail|nullable|string';
            $meta_rules['card_number'] = ['bail','required','string','max:32',Rule::unique('user_meta')->ignore($user->userMeta->id)];
            $meta_rules['expiration_date'] = 'bail|required|date|after:registration_date';
        }else{
            $meta_rules['registration_date'] = 'bail|required|date|after_or_equal:' . now()->toDateString();
            $meta_rules['expiration_date'] = 'bail|required|date|after:registration_date';
            $rules['username'] = 'bail|required|string|unique:users';
            $rules['email'] = 'bail|required|email|unique:users';
            $rules['password'] = 'bail|required|string';
            $meta_rules['card_number'] = 'bail|required|string|max:32|unique:user_meta';
        }

        return array_merge($rules,$meta_rules);
    }
}
