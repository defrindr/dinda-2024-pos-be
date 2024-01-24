<?php

namespace App\Http\Controllers\Master;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\PelangganRequest;
use App\Http\Services\PelangganService;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        // response pagination
        $pagination = PelangganService::paginate($request);
        return ResponseHelper::successWithData($pagination);
    }

    public function show(Pelanggan $pelanggan)
    {
        return ResponseHelper::successWithData($pelanggan, 'Data found');
    }

    public function store(PelangganRequest $request)
    {
        try {
            $success = PelangganService::create($request);
            if ($success)
                return ResponseHelper::successWithData(null, 'Kategori berhasil dibuat');
            else return ResponseHelper::badRequest(null, 'Gagal menyimpan data');
        } catch (\Throwable $th) {
            // simpan log untuk tracing error
            Log::error($th->getMessage());
            return ResponseHelper::error($th, 'Terjadi kesalahan saat menjalankan aksi');
        }
    }

    public function update(PelangganRequest $request, Pelanggan $pelanggan)
    {
        try {
            $success = PelangganService::update($pelanggan, $request);
            if ($success)
                return ResponseHelper::successWithData(null, 'Kategori berhasil diubah');
            else return ResponseHelper::badRequest(null, 'Gagal mengubah data');
        } catch (\Throwable $th) {
            // simpan log untuk tracing error
            Log::error($th->getMessage());
            return ResponseHelper::error($th, 'Terjadi kesalahan saat menjalankan aksi');
        }
    }

    public function destroy(Pelanggan $pelanggan)
    {
        try {
            // hapus data
            $pelanggan->delete();
            return response()->json(['message' => 'Kategori berhasil dihapus'], 200);
        } catch (\Throwable $th) {
            // simpan log untuk tracing error
            Log::error($th->getMessage());
            return ResponseHelper::error($th, 'Terjadi kesalahan saat menjalankan aksi');
        }
    }
}
