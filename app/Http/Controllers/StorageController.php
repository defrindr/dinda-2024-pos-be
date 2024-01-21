<?php

namespace App\Http\Controllers;

use App\Models\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StorageController extends Controller
{
    function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        $pagination = Storage::orderBy('id', 'desc')->search($request->get('search'))->paginate(10);
        return response()->json($pagination);
    }

    public function show(Storage $storage)
    {
        return response()->json(['data' => $storage, 'message' => 'Data ditemukan']);
    }

    public function store(Request $request)
    {
        $payloads = $request->only('name', 'location');
        try {
            Storage::create($payloads);
            return response()->json(['message' => 'Storage berhasil dibuat'], 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat menjalankan aksi'], 400);
        }
    }

    public function update(Request $request, Storage $storage)
    {
        $payloads = $request->only('name', 'location');
        try {
            $storage->update($payloads);
            return response()->json(['message' => 'Storage berhasil diubah'], 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat menjalankan aksi'], 400);
        }
    }

    public function destroy(Storage $storage)
    {
        try {
            $storage->delete();
            return response()->json(['message' => 'Storage berhasil dihapus'], 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat menjalankan aksi'], 400);
        }
    }
}
