<?php

namespace App\Http\Resources;

use App\Helpers\CurrencyHelper;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $resource = parent::toArray($request);

        // currency formatter
        $resource['price'] = CurrencyHelper::rupiah($resource['price']);
        $resource['total_price'] = CurrencyHelper::rupiah($resource['total_price']);

        // jika route detail, tampilkan keseluruhan resource
        // if ($request->routeIs('transaction.show')) {
        $product = Product::where('id', $resource['product_id'])->first();
        if ($product) {
            $resource['product'] = new ProductResource($product);
        } else {
            $resource['product'] = null;
        }

        unset($resource['transaction_id']);
        // }

        return $resource;
    }
}
