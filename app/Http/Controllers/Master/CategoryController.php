<?php

namespace App\Http\Controllers\Master;

use App\Helpers\PaginationHelper;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryCollection;
use App\Http\Services\CategoryService;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        // response pagination
        $pagination = CategoryService::paginate($request);
        return ResponseHelper::successWithData($pagination);
    }

    public function show(Category $category)
    {
        return ResponseHelper::successWithData($category, 'Data found');
    }

    public function store(CategoryRequest $request)
    {
        try {
            $successSaveCategory = CategoryService::create($request);
            if ($successSaveCategory)
                return ResponseHelper::successWithData(null, 'Kategori berhasil dibuat');
            else return ResponseHelper::badRequest(null, 'Gagal menyimpan data');
        } catch (\Throwable $th) {
            // simpan log untuk tracing error
            Log::error($th->getMessage());
            return ResponseHelper::error($th, 'Terjadi kesalahan saat menjalankan aksi');
        }
    }

    public function update(CategoryRequest $request, Category $category)
    {
        try {
            $successUpdateCategory = CategoryService::update($category, $request);
            if ($successUpdateCategory)
                return ResponseHelper::successWithData(null, 'Kategori berhasil diubah');
            else return ResponseHelper::badRequest(null, 'Gagal mengubah data');
        } catch (\Throwable $th) {
            // simpan log untuk tracing error
            Log::error($th->getMessage());
            return ResponseHelper::error($th, 'Terjadi kesalahan saat menjalankan aksi');
        }
    }

    public function destroy(Category $category)
    {
        try {
            // hapus data
            $category->delete();
            return response()->json(['message' => 'Kategori berhasil dihapus'], 200);
        } catch (\Throwable $th) {
            // simpan log untuk tracing error
            Log::error($th->getMessage());
            return ResponseHelper::error($th, 'Terjadi kesalahan saat menjalankan aksi');
        }
    }
}
