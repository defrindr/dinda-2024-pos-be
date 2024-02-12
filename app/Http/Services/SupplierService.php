<?php

namespace App\Http\Services;

use App\Helpers\PaginationHelper;
use App\Http\Resources\SupplierCollection;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierService
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
        $pagination = Supplier::orderBy('id', $sort)
            ->search($keyword)
            ->paginate($perPage);

        return new SupplierCollection($pagination);
    }

    public static function create(Request $request): bool
    {
        $payload = $request->only('code', 'name', 'phone', 'address', 'status');

        return Supplier::create($payload) ? true : false;
    }

    public static function update(Supplier $supplier, Request $request): bool
    {
        $payload = $request->only('code', 'name', 'phone', 'address', 'status');

        return $supplier->update($payload);
    }
}
