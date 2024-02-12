<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Login dengan kembalian token
     */
    public function login(LoginRequest $request): JsonResponse
    {
        // generate token
        $token = auth('api')->attempt($request->only(['username', 'password']));

        // gagal login
        if (! $token) {
            return ResponseHelper::badRequest('The username and password not match');
        }

        // retrun token
        return $this->respondWithToken($token);
    }

    /**
     * Mendapatkan profil dari pengguna
     */
    public function me(): JsonResponse
    {
        $user = auth('api')->user();

        return ResponseHelper::successWithData($user, 'Profile successful fetched');
    }

    /**
     * Melakukan refresh token, ketika sudah expired
     */
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth()?->refresh());
    }

    /**
     * Logout dari aplikasi
     */
    public function logout(): JsonResponse
    {
        auth('api')->logout();

        return ResponseHelper::successWithData(null, 'Successfully logged out');
    }

    /**
     * Generate response token
     */
    protected function respondWithToken($token): JsonResponse
    {
        return ResponseHelper::success([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')?->factory()?->getTTL() * 60,
        ]);
    }
}
