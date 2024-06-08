<?php

namespace Database\Seeders;

use App\Models\Pelanggan;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $length = 100;

        DB::beginTransaction();

        $this->generateTransaction($length);
        DB::commit();
    }

    public function generateTransaction(int $length)
    {
        for ($i = 0; $i < $length; $i++) {
            $transaction = new Transaction;

            $transaction->kasir_id = User::inRandomOrder()->first()->id;
            $transaction->customer_id = Pelanggan::inRandomOrder()->first()->id;
            $transaction->invoice = date('YmdHis' . random_int(0, 99999));
            $transaction->date = date('Y-m-d');
            $transaction->total_price = 0;
            $transaction->total_pay = 0;
            $transaction->total_return = 0;
            $transaction->save();
            $itemLength = random_int(1, 5);

            for ($j = 0; $j < $itemLength; $j++) {
                $product = Product::inRandomOrder()->first();
                $quantity = random_int(1, 10);

                $detail = new TransactionDetail();

                $detail->product_id = $product->id;
                $detail->satuan = $product->satuan_ecer;
                $detail->transaction_id = $transaction->id;
                $detail->price = $product->harga_ecer;
                $detail->quantity = $quantity;
                $detail->total_price = $product->harga_ecer * $quantity;
                $detail->save();

                $transaction->total_price += $detail->total_price;
            }

            $margin = random_int(1, 30) * 1000;
            $transaction->total_return = $margin;
            $transaction->total_pay = $transaction->total_price + $margin;
            $val = 10 - $i;
            $transaction->date =  date("Y-m-d", strtotime(date("Y-m-d") . " {$val} days"));

            $transaction->save();
        }
    }
}
