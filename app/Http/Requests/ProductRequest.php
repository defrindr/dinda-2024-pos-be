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
            'stock_pack' => 'required|numeric',
            'satuan_pack' => 'required|string',
            'per_pack' => 'required|numeric',
            'harga_pack' => 'required|numeric',
            'harga_ecer' => 'required|numeric',
            'jumlah_ecer' => 'required|numeric',
            'satuan_ecer' => 'required|string',
            'harga_beli' => 'required|numeric',
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
