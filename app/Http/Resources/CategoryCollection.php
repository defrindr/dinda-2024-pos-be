<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryCollection extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'items' => CategoryResource::collection($this->collection),
            'meta' => [
                "currentPage" =>  $this->currentPage(),
                "total" =>  $this->total(),
                "perPage" =>  $this->perPage(),
                "path" =>  $this->path(),
                "totalPage" =>  ceil($this->total() / $this->perPage()),
            ],
        ];
    }
}
