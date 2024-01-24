<?php

namespace App\Http\Controllers\Master;

use App\Helpers\PaginationHelper;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryCollection;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        // prepare parameter
        $keyword = $request->get('search');
        $perPage = PaginationHelper::perPage($request);
        $sort    = PaginationHelper::sortCondition($request, PaginationHelper::SORT_DESC);

        // query database
        $pagination = Category::orderBy('id', $sort)
            ->search($keyword)
            ->paginate($perPage);

        // response
        return ResponseHelper::successWithData(new CategoryCollection($pagination));
    }

    public function show(Category $category)
    {
        return ResponseHelper::successWithData($category, 'Data found');
    }

    public function store(Request $request)
    {
        // mendapatkan parameter
        $payloads = $request->only('name', 'parent_id');

        try {
            // menambahkan data sesuai payload
            Category::create($payloads);
            return ResponseHelper::successWithData(null, 'Kategori berhasil dibuat');
        } catch (\Throwable $th) {
            // simpan log untuk tracing error
            Log::error($th->getMessage());
            return ResponseHelper::error($th, 'Terjadi kesalahan saat menjalankan aksi');
        }
    }

    public function update(Request $request, Category $category)
    {
        // mendapatkan parameter
        $payloads = $request->only('name', 'parent_id');

        try {
            // ubah data yang dipilih, sesuai dengan payload
            $category->update($payloads);
            return ResponseHelper::successWithData(null, 'Kategori berhasil diubah');
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
