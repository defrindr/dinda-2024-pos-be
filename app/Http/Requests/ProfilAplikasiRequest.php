<?php

namespace App\Http\Requests;

class ProfilAplikasiRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'nama_aplikasi' => 'required|string',
            'alamat' => 'required|string',
            'no_telp' => 'required|string',
            'website' => 'required|string',
            'logo' => 'nullable|file|mimes:jpg,png,gif',
        ];
    }
}
