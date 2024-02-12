<?php

namespace App\Http\Controllers\Master;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Services\CategoryService;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    /**
     * Menampilkan data kategori dengan paginasi
     */
    public function index(Request $request): JsonResponse
    {
        // response pagination
        $pagination = CategoryService::paginate($request);

        return ResponseHelper::successWithData($pagination);
    }

    /**
     * Menampilkan data kategori sesuai dengan parameter [id]
     */
    public function show(Category $category): JsonResponse
    {
        return ResponseHelper::successWithData(
            new CategoryResource($category),
            'Data kategori berhasil ditemukan'
        );
    }

    /**
     * Menambahkan data kategori baru
     */
    public function store(CategoryRequest $request): JsonResponse
    {
        try {
            if (CategoryService::create($request)) {
                return ResponseHelper::successWithData(null, 'Kategori berhasil dibuat', 201);
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
     * Melakukan modifikasi terhadap kategori yang dipilih
     *
     * @param  Category  $category  Kategori yang ingin diubah
     */
    public function update(CategoryRequest $request, Category $category): JsonResponse
    {
        try {
            if (CategoryService::update($category, $request)) {
                return ResponseHelper::successWithData(null, 'Kategori berhasil diubah');
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
     * Menghapus kategori yang dipilih
     *
     * @param  Category  $category  Kategori yang dipilih
     */
    public function destroy(Category $category): JsonResponse
    {
        try {
            $category->delete();

            return ResponseHelper::successWithData(null, 'Kategori berhasil dihapus');
        } catch (\Throwable $th) {
            // simpan log untuk tracing error
            Log::error($th->getMessage());

            return ResponseHelper::error($th, 'Terjadi kesalahan saat menjalankan aksi');
        }
    }
}
