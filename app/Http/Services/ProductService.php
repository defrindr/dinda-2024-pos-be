<?php

namespace App\Http\Services;

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
        $products = Product::orderBy('name', 'asc')->get();

        $all_products = [];
        if ($request->get('search')) {
            $selected_products = self::sequential($products, $request->get('search'));
            // dd($selected_products);

            if (count($selected_products)) {
                $all_products = $selected_products;
            }
        } else {
            $all_products = $products;
        }

        return new ProductCollection($all_products);
    }

    public static function sequential($arr, $x)
    {
        $existingProduct = [];
        foreach ($arr as $item) {
            $pos1 = strpos(strtolower($item->name), strtolower($x));
            $pos2 = strpos(strtolower($item->code), strtolower($x));

            if ($pos1 !== false || $pos2 !== false) {
                $existingProduct[] = $item;
            }
        }

        return $existingProduct;
    }

    private static function binarySearch($arr, $x)
    {
        $l = 0;
        $r = count($arr);
        // Loop to implement Binary Search
        while ($l <= $r) {

            // Calculatiing mid
            $m = $l + (int) (($r - $l) / 2);
            if ($m == count($arr)) {
                return -1;
            }

            // if (count($arr) < 100)
            //     print($arr . "\n\n\n\n");
            // print($l . " " . $r . "<br/><br/>");
            $maxLength = strlen($arr[$m]->name) > strlen($x) ? strlen($x) : strlen($arr[$m]->name);

            $res = strncmp(strtolower($x), strtolower($arr[$m]->name), $maxLength);
            // print("{$arr[$m]->name}\n{$x}\n$res");

            // Check if x is present at mid
            if ($res == 0) {
                return $m;
            }

            // If x greater, ignore left half
            if ($res > 0) {
                $l = $m + 1;
            }

            // If x is smaller, ignore right half
            else {
                $r = $m - 1;
            }
        }

        return -1;
    }

    public static function create(Request $request): bool
    {
        $payload = $request->validated();

        $responseUpload = RequestHelper::uploadImage($request->file('photo'), Product::getRelativePath());
        if (! $responseUpload['success']) {
            throw new BadRequestHttpException('Gagal mengunggah gambar');
        }

        $payload['photo'] = $responseUpload['fileName'];

        return Product::create($payload) ? true : false;
    }

    public static function update(Product $product, Request $request): bool
    {
        $payload = $request->validated();
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
