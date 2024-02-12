<?php

namespace App\Http\Services;

use App\Helpers\RequestHelper;
use App\Http\Resources\ProfilAplikasiResource;
use App\Models\ProfilAplikasi;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ProfilAplikasiService
{
    public static function first(): JsonResource
    {
        return new ProfilAplikasiResource(ProfilAplikasi::first());
    }

    public static function update(Request $request): bool
    {
        // mendapatkan data yang ingin diupdate
        $profil = ProfilAplikasi::first();

        // prepare parameter
        $payload = $request->only('nama_aplikasi', 'address', 'phone', 'website');

        if ($request->hasFile('logo')) {
            // unggah gambar
            $responseUpload = RequestHelper::uploadImage($request->file('logo'), ProfilAplikasi::getRelativePath());
            if (! $responseUpload['success']) {
                throw new BadRequestHttpException('Gagal mengunggah gambar');
            }

            $payload['logo'] = $responseUpload['fileName'];
        } else {
            $payload['logo'] = $profil->logo;
        }

        // update
        return $profil->update($payload);
    }
}
