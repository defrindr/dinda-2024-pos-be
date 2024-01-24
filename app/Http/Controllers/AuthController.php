<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

  public function __construct()
  {
    $this->middleware('auth:api', ['except' => ['login']]);
  }

  /**
   * Login dengan kembalian token
   */
  public function login(Request $request)
  {
    // validasi request
    $validator = Validator::make($request->all(), [
      'username' => 'required',
      'password' => 'required',
    ]);

    if ($validator->fails()) {
      return ResponseHelper::validationError($validator->errors());
    }

    // generate token
    $token = auth('api')->attempt($validator->validated());

    // gagal login
    if (!$token) {
      return ResponseHelper::badRequest('The username and password not match');
    }

    // retrun token
    return $this->respondWithToken($token);
  }

  /**
   * Mendapatkan profil dari pengguna
   */
  public function me()
  {
    $user = auth('api')->user();
    return ResponseHelper::successWithData($user, 'Profile successful fetched');
  }

  /**
   * Melakukan refresh token, ketika sudah expired
   */
  public function refresh()
  {
    return $this->respondWithToken(auth()?->refresh());
  }

  /**
   * Logout dari aplikasi
   */
  public function logout()
  {
    auth('api')->logout();
    return ResponseHelper::successWithData(null, 'Successfully logged out');
  }

  /**
   * Generate response token
   */
  protected function respondWithToken($token)
  {
    return ResponseHelper::success([
      'access_token' => $token,
      'token_type' => 'bearer',
      'expires_in' => auth('api')?->factory()?->getTTL() * 60
    ]);
  }
}
