<?php

namespace App\Http\Requests;

class ProductRequest extends BaseFormRequest
{
    public function rules(): array
    {
        $rules = [
            'category_id' => 'required|numeric',
            'code' => 'required|string',
            'name' => 'required|string',
            'unit' => 'required|string',
            'stock' => 'required|string',
            'price_buy' => 'required|numeric',
            'price_sell' => 'required|numeric',
            'description' => 'required|string',
            'date' => 'required|date',
            'photo' => 'required|file|mimes:jpg,png,gif',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $rules['photo'] = 'nullable|file|mimes:jpg,png,gif';
        }

        return $rules;
    }
}
