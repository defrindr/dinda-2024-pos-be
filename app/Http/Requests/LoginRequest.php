<?php

namespace App\Http\Requests;

class LoginRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'username' => 'required',
            'password' => 'required',
        ];
    }
}
