<?php

namespace App\Http\Controllers\Master;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\SupplierRequest;
use App\Http\Resources\SupplierResource;
use App\Http\Services\SupplierService;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SupplierController extends Controller
{
    /**
     * Menampilkan list data supplier
     */
    public function index(Request $request): JsonResponse
    {
        return ResponseHelper::successWithData(
            SupplierService::paginate($request)
        );
    }

    /**
     * Menampilkan informasi supplier yang dipilih
     */
    public function show(Supplier $supplier): JsonResponse
    {
        return ResponseHelper::successWithData(
            new SupplierResource($supplier),
            'Data supplier berhasil ditemukan'
        );
    }

    /**
     * Menambahkan data supplier baru
     */
    public function store(SupplierRequest $request): JsonResponse
    {
        try {
            if (SupplierService::create($request)) {
                return ResponseHelper::successWithData(null, 'Supplier berhasil dibuat', 201);
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
     * Mengubah data supplier yang dipilih
     */
    public function update(SupplierRequest $request, Supplier $supplier): JsonResponse
    {
        try {
            if (SupplierService::update($supplier, $request)) {
                return ResponseHelper::successWithData(null, 'Supplier berhasil diubah');
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
     * Menghapus data supplier yang dipilih
     */
    public function destroy(Supplier $supplier): JsonResponse
    {
        try {
            $supplier->delete();

            return ResponseHelper::success('Supplier berhasil dihapus');
        } catch (\Throwable $th) {
            // simpan log untuk tracing error
            Log::error($th->getMessage());

            return ResponseHelper::error($th, 'Terjadi kesalahan saat menjalankan aksi');
        }
    }
}
