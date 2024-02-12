<?php

namespace App\Http\Requests;

class PelangganRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'code' => 'required|string',
            'name' => 'required|min:3',
            'phone' => 'required|string',
            'address' => 'required|string',
            'gender' => 'required|in:L,P',
            'dob' => 'required|date',
            'status' => 'required|in:active,nonactive',
        ];
    }
}
