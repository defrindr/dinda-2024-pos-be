<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        $pagination = Product::orderBy('id', 'desc')->search($request->get('search'))->paginate(10);
        return response()->json(new ProductCollection($pagination));
    }

    public function show(Product $product)
    {
        return response()->json(['data' => new ProductResource($product), 'message' => 'Data ditemukan']);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sku' => 'required',
            'name' => 'required',
            'price' => 'required',
            'category_id' => 'required',
            'storage_id' => 'required',
            'stock' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()->first()], 400);
        }

        $payloads = $request->only('sku', 'name', 'price', 'category_id', 'storage_id', 'stock');
        try {
            Product::create($payloads);
            return response()->json(['message' => 'Product berhasil dibuat'], 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat menjalankan aksi'], 400);
        }
    }

    public function update(Request $request, Product $product)
    {
        $payloads = $request->only('sku', 'name', 'price', 'category_id', 'storage_id', 'stock');
        try {
            $product->update($payloads);
            return response()->json(['message' => 'Product berhasil diubah'], 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat menjalankan aksi'], 400);
        }
    }

    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return response()->json(['message' => 'Product berhasil dihapus'], 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat menjalankan aksi'], 400);
        }
    }
}
