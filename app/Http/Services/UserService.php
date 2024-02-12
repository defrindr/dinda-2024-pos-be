<?php

namespace App\Http\Services;

use App\Helpers\PaginationHelper;
use App\Helpers\RequestHelper;
use App\Http\Resources\UserCollection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UserService
{
    /**
     * Fungsi untuk melakukan paginasi dan filter list data
     *
     * @param  Request  $request  request dari pengguna
     */
    public static function paginate(Request $request): ResourceCollection
    {
        // prepare parameter query
        $keyword = $request->get('search');
        $perPage = PaginationHelper::perPage($request);
        $sort = PaginationHelper::sortCondition($request, PaginationHelper::SORT_DESC);

        // query database
        $pagination = User::orderBy('id', $sort)
            ->search($keyword)
            ->paginate($perPage);

        // collect pagination
        return new UserCollection($pagination);
    }

    /**
     * Fungsi untuk menambahkan data baru
     *
     * @param  Request  $request  request dari pengguna
     */
    public static function create(Request $request): bool
    {
        // spesifikan kolom yang akan di gunakan
        $payload = $request->only('username', 'password', 'code', 'name', 'email', 'phone', 'photo', 'role');

        // convert password ke hash
        $payload['password'] = Hash::make($payload['password']);

        // unggah gambar
        $responseUpload = RequestHelper::uploadImage($request->file('photo'), User::getRelativeAvatarPath());

        if (! $responseUpload['success']) {
            throw new BadRequestHttpException('Gagal mengunggah gambar');
        }

        $payload['photo'] = $responseUpload['fileName'];

        // simpan ke database
        return User::create($payload) ? true : false;
    }

    /**
     * Fungsi untuk mengubah data
     *
     * @param  Request  $request  request dari pengguna
     */
    public static function update(User $user, Request $request): bool
    {
        // spesifikan kolom yang akan di update
        $payload = $request->only('code', 'name', 'username', 'email', 'phone', 'password', 'photo', 'role');

        // jika terdapat password, maka convert ke hash
        if (isset($payload['password'])) {
            $payload['password'] = Hash::make($payload['password']);
        } else {
            $payload['password'] = $user->password;
        }

        // simpan foto ke storage
        if ($request->hasFile('photo')) {
            $responseUpload = RequestHelper::uploadImage($request->file('photo'), User::getRelativeAvatarPath(), $user->photo);
            if (! $responseUpload['success']) {
                throw new BadRequestHttpException('Gambar gagal diunggah');
            }
            $payload['photo'] = $responseUpload['fileName'];
        } else {
            $payload['photo'] = $user->photo;
        }

        // update user di database
        return $user->update($payload);
    }
}
