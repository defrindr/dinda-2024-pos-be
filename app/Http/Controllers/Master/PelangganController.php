<?php

namespace App\Http\Controllers\Master;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\PelangganRequest;
use App\Http\Resources\PelangganResource;
use App\Http\Services\PelangganService;
use App\Models\Pelanggan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PelangganController extends Controller
{
    /**
     * Menampilkan data pelanggan dengan paginasi
     */
    public function index(Request $request): JsonResponse
    {
        // response pagination
        $pagination = PelangganService::paginate($request);

        return ResponseHelper::successWithData($pagination);
    }

    /**
     * Menampilkan data pelanggan yang dipilih
     */
    public function show(Pelanggan $pelanggan): JsonResponse
    {
        return ResponseHelper::successWithData(
            new PelangganResource($pelanggan),
            'Data Pelanggan berhasil ditemukan'
        );
    }

    /**
     * Menambahkan data pelanggan baru
     */
    public function store(PelangganRequest $request): JsonResponse
    {
        try {
            if (PelangganService::create($request)) {
                return ResponseHelper::successWithData(null, 'Pelanggan berhasil dibuat', 201);
            } else {
                return ResponseHelper::badRequest(null, 'Gagal menyimpan data');
            }
        } catch (\Throwable $th) {
            // simpan log untuk tracing error
            Log::error($th->getMessage());

            return ResponseHelper::error($th, 'Terjadi kesalahan saat menjalankan aksi');
        }
    }

    /**
     * Mengubah data pelanggan yang dipilih
     */
    public function update(PelangganRequest $request, Pelanggan $pelanggan): JsonResponse
    {
        try {
            if (PelangganService::update($pelanggan, $request)) {
                return ResponseHelper::successWithData(null, 'Pelanggan berhasil diubah');
            } else {
                return ResponseHelper::badRequest(null, 'Gagal mengubah data');
            }
        } catch (\Throwable $th) {
            // simpan log untuk tracing error
            Log::error($th->getMessage());

            return ResponseHelper::error($th, 'Terjadi kesalahan saat menjalankan aksi');
        }
    }

    /**
     * Menghapus data pelanggan yang dipilih
     */
    public function destroy(Pelanggan $pelanggan): JsonResponse
    {
        try {
            $pelanggan->delete();

            return ResponseHelper::successWithData(null, 'Pelanggan berhasil dihapus');
        } catch (\Throwable $th) {
            // simpan log untuk tracing error
            Log::error($th->getMessage());

            return ResponseHelper::error($th, 'Terjadi kesalahan saat menjalankan aksi');
        }
    }
}
