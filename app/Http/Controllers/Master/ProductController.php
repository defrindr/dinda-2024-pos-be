<?php

namespace App\Http\Controllers\Master;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Http\Services\ProductService;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Menampilkan data produk dengan paginasi
     */
    public function index(Request $request): JsonResponse
    {
        return ResponseHelper::successWithData(
            ProductService::paginate($request)
        );
    }

    /**
     * Menampilkan produk yang dipilih
     */
    public function show(Product $product): JsonResponse
    {
        return ResponseHelper::successWithData(
            new ProductResource($product),
            'Produk berhasil ditemukan'
        );
    }

    /**
     * Menambahkan produk baru
     */
    public function store(ProductRequest $request): JsonResponse
    {
        try {
            if (ProductService::create($request)) {
                return ResponseHelper::successWithData(null, 'Produk berhasil dibuat', 201);
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
     * Mengubah produk yang dipilih
     */
    public function update(ProductRequest $request, Product $product): JsonResponse
    {
        try {
            if (ProductService::update($product, $request)) {
                return ResponseHelper::successWithData(null, 'Produk berhasil diubah');
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
     * Mengubah produk yang dipilih
     */
    public function destroy(Product $product): JsonResponse
    {
        try {
            $product->delete();

            return ResponseHelper::successWithData(null, 'Produk berhasil dihapus');
        } catch (\Throwable $th) {
            // simpan log untuk tracing error
            Log::error($th->getMessage());

            return ResponseHelper::error($th, 'Terjadi kesalahan saat menjalankan aksi');
        }
    }
}
