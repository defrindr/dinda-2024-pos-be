<?php

namespace App\Http\Resources;

use App\Models\ProfilAplikasi;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfilAplikasiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $parent = parent::toArray($request);
        $parent['logo'] = asset_storage(ProfilAplikasi::getRelativePath().$this->logo);

        return $parent;
    }
}
