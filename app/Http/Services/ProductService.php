<?php

namespace App\Http\Services;

use App\Helpers\PaginationHelper;
use App\Helpers\RequestHelper;
use App\Http\Resources\ProductCollection;
use App\Models\Product;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ProductService
{
    /**
     * Fungsi untuk melakukan paginasi dan filter list data
     *
     * @param  Request  $request  request dari pengguna
     * @return bool
     */
    public static function paginate(Request $request)
    {
        // prepare parameter
        $keyword = $request->get('search');
        $perPage = PaginationHelper::perPage($request);
        $sort = PaginationHelper::sortCondition($request, PaginationHelper::SORT_DESC);

        // query database
        $pagination = Product::orderBy('id', $sort)
            ->search($keyword)
            ->paginate($perPage);

        return new ProductCollection($pagination);
    }

    public static function create(Request $request): bool
    {
        $payload = $request->only('category_id', 'code', 'name', 'unit', 'stock', 'price_buy', 'price_sell', 'description', 'date');

        $responseUpload = RequestHelper::uploadImage($request->file('photo'), Product::getRelativePath());
        if (! $responseUpload['success']) {
            throw new BadRequestHttpException('Gagal mengunggah gambar');
        }

        $payload['photo'] = $responseUpload['fileName'];

        return Product::create($payload) ? true : false;
    }

    public static function update(Product $product, Request $request): bool
    {
        $payload = $request->only('category_id', 'code', 'name', 'unit', 'stock', 'price_buy', 'price_sell', 'description', 'date');
        if ($request->hasFile('photo')) {
            $responseUpload = RequestHelper::uploadImage($request->file('photo'), Product::getRelativePath());
            if (! $responseUpload['success']) {
                throw new BadRequestHttpException('Gagal mengunggah gambar');
            }

            $payload['photo'] = $responseUpload['fileName'];
        } else {
            $payload['photo'] = $product->photo;
        }

        return $product->update($payload);
    }
}
