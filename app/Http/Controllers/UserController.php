<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $pagination = User::orderBy('id', 'desc')->paginate(20);
        return response()->json($pagination);
    }

    public function show(User $user)
    {
        return response()->json(['data' => $user, 'message' => 'Pengguna ditemukan']);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => "required|in:" . implode(",", array_keys(User::listRoles()))
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()->first()], 400);
        }

        $payloads = $request->only('name', 'email', 'password', 'role');
        $payloads['password'] = Hash::make($payloads['password']);

        try {
            User::create($payloads);
            return response()->json(['message' => 'User berhasil dibuat'], 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat menjalankan aksi'], 400);
        }
    }

    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'unique:users,username, ' . $user->id,
            'email' => 'email|unique:users,email, ' . $user->id,
            'password' => 'min:6',
            'role' => "in:" . implode(",", array_keys(User::listRoles()))
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()->first()], 400);
        }

        $payloads = $request->only('name', 'email', 'password', 'role');
        if (isset($payloads['password'])) {
            $payloads['password'] = Hash::make($payloads['password']);
        }

        try {
            $user->update($payloads);
            return response()->json(['message' => 'User berhasil diubah'], 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat menjalankan aksi'], 400);
        }
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();
            return response()->json(['message' => 'User berhasil dihapus'], 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat menjalankan aksi'], 400);
        }
    }
}
