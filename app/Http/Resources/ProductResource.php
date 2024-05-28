<?php

namespace App\Http\Resources;

use App\Helpers\CurrencyHelper;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
        $resource['_harga_pack'] = CurrencyHelper::rupiah($resource['harga_pack']);
        $resource['_harga_ecer'] = CurrencyHelper::rupiah($resource['harga_ecer']);
        $resource['_harga_beli'] = CurrencyHelper::rupiah($resource['harga_beli']);

        $resource['category'] = Category::where('id', $this->category_id)->first();
        $resource['photo'] = asset_storage(Product::getRelativePath().$this->photo);

        return $resource;
    }
}
