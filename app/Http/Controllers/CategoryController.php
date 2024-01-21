<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        $pagination = Category::orderBy('id', 'desc')->search($request->get('search'))->paginate(10);
        return response()->json($pagination);
    }

    public function show(Category $category)
    {
        return response()->json(['data' => $category, 'message' => 'Data ditemukan']);
    }

    public function store(Request $request)
    {
        $payloads = $request->only('name', 'parent_id');
        try {
            Category::create($payloads);
            return response()->json(['message' => 'Category berhasil dibuat'], 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat menjalankan aksi'], 400);
        }
    }

    public function update(Request $request, Category $category)
    {
        $payloads = $request->only('name', 'parent_id');
        try {
            $category->update($payloads);
            return response()->json(['message' => 'Category berhasil diubah'], 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat menjalankan aksi'], 400);
        }
    }

    public function destroy(Category $category)
    {
        try {
            $category->delete();
            return response()->json(['message' => 'Category berhasil dihapus'], 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat menjalankan aksi'], 400);
        }
    }
}
