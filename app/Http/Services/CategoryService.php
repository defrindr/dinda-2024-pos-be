<?php

namespace App\Http\Services;

use App\Helpers\PaginationHelper;
use App\Http\Resources\CategoryCollection;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CategoryService
{
    /**
     * Fungsi untuk melakukan paginasi dan filter list data
     *
     * @param  Request  $request  request dari pengguna
     * @return bool
     */
    public static function paginate(Request $request): ResourceCollection
    {
        // prepare parameter
        $keyword = $request->get('search');
        $perPage = PaginationHelper::perPage($request);
        $sort = PaginationHelper::sortCondition($request, PaginationHelper::SORT_DESC);

        // query database
        $pagination = Category::orderBy('id', $sort)
            ->search($keyword)
            ->paginate($perPage);

        // response
        return new CategoryCollection($pagination);
    }

    /**
     * Fungsi untuk menambahkan data baru
     *
     * @param  Request  $request  request dari pengguna
     */
    public static function create(Request $request): bool
    {
        // mendapatkan parameter
        $payload = $request->only('name');

        // nama kategori harus unik
        $isDataExist = Category::where(['name' => $payload['name']])->first();
        if ($isDataExist) {
            throw new BadRequestHttpException("Kategori dengan nama {$payload['name']} telah tersedia.");
        }

        return Category::create($payload) ? true : false;
    }

    /**
     * Fungsi untuk mengubah data
     *
     * @param  Request  $request  request dari pengguna
     */
    public static function update(Category $category, Request $request): bool
    {
        // mendapatkan parameter
        $payload = $request->only('name');

        // nama kategori harus unik
        $isDataExist = Category::where(['name' => $payload['name']])->where('id', '!=', $category->id)->first();
        if ($isDataExist) {
            throw new BadRequestHttpException("Kategori dengan nama {$payload['name']} telah tersedia.");
        }

        return $category->update($payload);
    }
}
