<?php

namespace App\Http\Requests;

class TrasanctionRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'items.*.product_id' => 'required|numeric',
            'items.*.amount' => 'required|numeric',

            'customer_id' => 'required',
            'pay' => 'required|numeric',
        ];
    }
}
