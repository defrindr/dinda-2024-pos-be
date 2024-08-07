<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TransactionCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'items' => TransactionResource::collection($this->collection),
            'meta' => [
                'currentPage' => $this->currentPage(),
                'total' => $this->total(),
                'perPage' => $this->perPage(),
                'path' => $this->path(),
                'totalPage' => ceil($this->total() / $this->perPage()),
            ],
        ];
    }
}
