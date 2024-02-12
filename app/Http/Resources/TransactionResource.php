<?php

namespace App\Http\Resources;

use App\Helpers\DateHelper;
use App\Models\Pelanggan;
use App\Models\TransactionDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $resource = parent::toArray($request);

        $resource['_date'] = DateHelper::readableDate($resource['date']);

        // jika route detail, tampilkan keseluruhan resource
        // if ($request->routeIs('transaction.show')) {
        $pelanggan = Pelanggan::where('id', $resource['customer_id'])->first();
        if ($pelanggan) {
            $resource['pelanggan'] = new PelangganResource($pelanggan);
        } else {
            $resource['pelanggan'] = null;
        }

        $cashier = User::where('id', $resource['kasir_id'])->first();
        if ($cashier) {
            $resource['kasir'] = new UserResource($cashier);
        } else {
            $resource['kasir'] = null;
        }

        $items = TransactionDetail::where('transaction_id', $resource['id'])->get();
        $resource['items'] = TransactionDetailResource::collection($items);
        // }

        return $resource;
    }
}
