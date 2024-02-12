<?php

namespace App\Http\Requests;

class SupplierRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'code' => 'required|string',
            'name' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'status' => 'required|in:active,nonactive',
        ];
    }
}
