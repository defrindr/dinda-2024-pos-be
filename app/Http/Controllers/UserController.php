<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\UserRequest;
use App\Http\Services\UserService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Mendapatkan data seluruh pengguna
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        return UserService::paginate($request);
    }

    /**
     * Mendapatkan pengguna dengan berdasarkan {id}
     *
     * @return JsonResponse
     */
    public function show(User $user)
    {
        // response
        return ResponseHelper::successWithData($user, 'Pengguna ditemukan');
    }

    /**
     * Menambahkan user baru ke aplikasi
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(UserRequest $request)
    {
        // simpan ke database
        try {
            $success = UserService::create($request);

            if ($success) {
                return ResponseHelper::successWithData(null, 'User berhasil dibuat', 201);
            } else {
                return ResponseHelper::badRequest(null, 'Gagal menambahkan data !');
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return ResponseHelper::error($th, 'Terjadi kesalahan saat menjalankan aksi');
        }
    }

    /**
     * Mengubah data pengguna dengan berdasarkan {id}
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function update(UserRequest $request, User $user)
    {
        // simpan ke database
        try {
            $success = UserService::update($user, $request);

            if ($success) {
                return ResponseHelper::successWithData(null, 'User berhasil diubah');
            }

            return ResponseHelper::badRequest(null, 'Gagal mengubah data !');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return ResponseHelper::error($th, 'Terjadi kesalahan saat menjalankan aksi');
        }
    }

    /**
     * Menghapus data user berdasarkan dengan {id}
     *
     * @return JsonResponse
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();

            return ResponseHelper::successWithData(null, 'User berhasil dihapus');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return ResponseHelper::error($th, 'Terjadi kesalahan saat menjalankan aksi');
        }
    }
}
