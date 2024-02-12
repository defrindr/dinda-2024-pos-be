<?php

namespace App\Http\Controllers\Master;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfilAplikasiRequest;
use App\Http\Services\ProfilAplikasiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
    /**
     * Mendapatkan informasi terkait dengan profil aplikasi
     */
    public function index(): JsonResponse
    {
        return ResponseHelper::successWithData(
            ProfilAplikasiService::first(),
            'Profil aplikasi berhasil didapatkan'
        );
    }

    /**
     * Mengubah profil aplikasi
     */
    public function update(ProfilAplikasiRequest $request): JsonResponse
    {
        try {
            if (ProfilAplikasiService::update($request)) {
                return ResponseHelper::successWithData(null, 'Profil Aplikasi berhasil diubah');
            } else {
                return ResponseHelper::badRequest(null, 'Gagal mengubah data');
            }
        } catch (\Throwable $th) {
            // simpan log untuk tracing error
            Log::error($th->getMessage());

            return ResponseHelper::error($th, 'Terjadi kesalahan saat menjalankan aksi');
        }
    }
}
