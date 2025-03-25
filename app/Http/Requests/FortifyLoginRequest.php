<?php

namespace App\Http\Requests;

use Laravel\Fortify\Http\Requests\LoginRequest;

class FortifyLoginRequest extends LoginRequest
{
    public function rules()
    {
        return [
            'type'=>'required|in:staff,patron',
            'login_key' => 'required|string',
            'password' => 'required|string'
        ];
    }
}