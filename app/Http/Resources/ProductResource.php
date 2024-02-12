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
        $resource['_price_sell'] = CurrencyHelper::rupiah($resource['price_sell']);
        $resource['_price_buy'] = CurrencyHelper::rupiah($resource['price_buy']);

        $resource['category'] = Category::where('id', $this->category_id)->first();
        $resource['photo'] = asset_storage(Product::getRelativePath().$this->photo);

        return $resource;
    }
}
