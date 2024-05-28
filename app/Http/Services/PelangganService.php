<?php

namespace App\Http\Services;

use App\Helpers\PaginationHelper;
use App\Http\Resources\PelangganCollection;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PelangganService
{
    /**
     * Fungsi untuk melakukan paginasi dan filter list data
     *
     * @param  Request  $request  request dari pengguna
     * @return bool
     */
    public static function paginate(Request $request): JsonResource
    {
        // prepare parameter
        $keyword = $request->get('search');
        $perPage = PaginationHelper::perPage($request);
        $sort = PaginationHelper::sortCondition($request, PaginationHelper::SORT_DESC);

        // query database
        $pagination = Pelanggan::orderBy('id', $sort)
            ->search($keyword)
            ->paginate($perPage);

        return new PelangganCollection($pagination);
    }

    public static function create(Request $request): bool
    {
        $payload = $request->only('nik', 'code', 'name', 'phone', 'address', 'gender', 'dob', 'status');

        return Pelanggan::create($payload) ? true : false;
    }

    public static function update(Pelanggan $pelanggan, Request $request): bool
    {
        $payload = $request->only('nik', 'code', 'name', 'phone', 'address', 'gender', 'dob', 'status');

        return $pelanggan->update($payload);
    }
}
