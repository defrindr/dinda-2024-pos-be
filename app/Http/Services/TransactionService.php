<?php

namespace App\Http\Services;

use App\Exceptions\ForbiddenHttpException;
use App\Http\Resources\TransactionCollection;
use App\Models\Pelanggan;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TransactionService
{
    /**
     * Mendapatkan list transaksi
     */
    public static function paginate(Request $request): JsonResource
    {
        $pagination = Transaction::orderBy('id', 'desc')
            ->search($request->get('search'))
            ->paginate(10);

        return new TransactionCollection($pagination);
    }

    /**
     * Menambahkan transaksi baru
     *
     * @return bool status pembuatan transaksi
     */
    public static function create(Request $request, User $cashier): bool
    {
        // aliasing items
        $items = $request->get('items');
        $paymentAmount = $request->get('pay');
        $memberId = $request->get('customer_id');

        DB::beginTransaction();
        // template transaksi
        $transaction = self::generateTemplateTransaction($cashier);
        if (!$transaction) {
            throw new BadRequestHttpException('Gagal membuat template transaksi');
        }

        // variable to store total purchase
        $totalPurchase = 0;

        foreach ($items as $item) {

            // aliasing variable
            $productId = $item['product_id'];
            $satuan = $item['satuan'];
            $amount = $item['amount'];

            // validasi product
            $product = self::productAvailable($productId, $satuan, $amount);

            // create detail transaction
            $totalPurchase = self::calculateAndCreateDetailItem(
                $transaction,
                $product,
                $totalPurchase,
                $amount,
                $satuan
            );
        }

        // member
        $memberId = self::haveJoinMember($memberId);

        $successUpdate = self::calculateTransactionPayment($transaction, $memberId, $totalPurchase, $paymentAmount);
        if (!$successUpdate) {
            throw new BadRequestHttpException('Gagal membuat transaksi');
        }

        DB::commit();

        return true;
    }

    /**
     * Mengunduh detail transaksi berdasarkan kode invoice
     */
    public static function downloadInvoice(string $invoiceCode): mixed
    {
        $transaction = Transaction::where('invoice', $invoiceCode)->first();
        if (!$transaction) {
            return false;
        }

        return FacadePdf::loadView('includes.invoice_template', compact('transaction'))->setOptions([
            'defaultFont' => 'sans-serif',
        ])->setPaper([0, 0, 300, 460], 'portrait');
    }

    /**
     * Membatalkan transaksi dan rollback data produk
     */
    public static function cancelTransaction(Transaction $transaction, User $cashier): bool
    {
        DB::beginTransaction();

        if ($transaction->kasir_id !== $cashier->id) {
            throw new ForbiddenHttpException('Akses ditolak anda bukan kasir yang menangani transaksi ini');
        }

        $items = $transaction->items()->get();
        foreach ($items as $item) {
            // kembalikan kuantitas ke produk
            $product = $item->product;
            $product->stock += $item->quantity;

            if ($product->save() == false) {
                Log::critical("cancelTransaction #{$transaction->id} - Kuantitas produk #{$product->id} gagal di ubah");
                DB::rollBack();

                return false;
            }
        }

        // gagal menyimpan data
        if ($transaction->delete() == false) {
            DB::rollBack();

            return false;
        }

        DB::commit();

        return true;
    }

    /**
     * Menghitung transaksi
     *
     * @param  Transaction  $transaction  Transaksi yang ingin dihitung
     * @param  int|null  $memnberId  Kartu anggota / pelanggan jika ada
     * @param  int  $totalPurchase  total pembelian
     * @param  $paymentAmount  uang dibayarkan
     * @return bool status update transaksi
     */
    protected static function calculateTransactionPayment(Transaction $transaction, ?int $memberId, int $totalPurchase, int $paymentAmount): bool
    {
        // check payment
        if ($totalPurchase > $paymentAmount) {
            throw new BadRequestHttpException("Jumlah pembayaran harus lebih dari $totalPurchase");
        }

        // calculate change amount
        $remainedPayment = $paymentAmount - $totalPurchase;

        // update transaction
        return $transaction->update([
            'total_price' => $totalPurchase,
            'price' => $totalPurchase,
            'discount' => 0,
            'total_pay' => $paymentAmount,
            'total_return' => $remainedPayment,
            'customer_id' => $memberId,
        ]);
    }

    /**
     * Melakukan pengecekan apakah customer terdaftar pada program membership
     *
     * @param  int|null  $memberId  id member jika ada
     * @return int|null valid member id, atau null jika belum terdaftar
     */
    protected static function haveJoinMember(?int $memberId): ?int
    {
        if ($memberId) {
            // check member exist
            $member = Pelanggan::where('id', $memberId)->first();
            if (!$member) {
                throw new BadRequestHttpException('Member tidak terdaftar pada sistem kami');
            }

            return $memberId;
        }

        return null;
    }

    /**
     * Menghitung harga, menambahkan detail transaksi
     * dan mengubah sisa stok produk
     *
     * @return int Total Purchase
     */
    protected static function calculateAndCreateDetailItem(Transaction $transaction, Product $product, int $totalPurchase, int $amount, int $satuan): int
    {
        // calculate price per item
        $pricePerItem = ($satuan == 1 ? $product->harga_pack : $product->harga_ecer) * $amount;
        $jumlahBeli =  $satuan == 1 ? $product->per_pack * $amount : $amount;

        // create detail based on transaction
        $transactionDetail = TransactionDetail::create([
            'product_id' => $product->id,
            'satuan' => $satuan == 1 ? $product->satuan_pack : $product->satuan_ecer,
            'transaction_id' => $transaction->id,
            'price' => $satuan == 1 ? $product->harga_pack : $product->harga_ecer,
            'quantity' => $amount,
            'total_price' => $pricePerItem,
        ]);

        // check detail transaction
        if (!$transactionDetail) {
            throw new BadRequestHttpException('Gagal menambahkan detail transaksi');
        }

        // update stok
        $success = $product->update(['stock' => $product->stock_pack - $jumlahBeli]);
        if (!$success) {
            throw new BadRequestHttpException('Gagal mengubah sisa stok');
        }

        return $totalPurchase + $pricePerItem;
    }

    /**
     * Cek apakah produk ada & stok cukup
     */
    protected static function productAvailable(int $productId, int $satuan, int $amount): Product
    {
        $product = Product::where('id', $productId)->first();

        if (!$product) {
            throw new BadRequestHttpException("Produk #{$productId} tidak ditemukan");
        }

        $jumlahBeli = $satuan == 1 ? $product->per_pack * $amount : $amount;

        if ($product->stock_pack < $jumlahBeli) {
            // stok kurang
            throw new BadRequestHttpException("Jumlah stok dari {$product->name} tidak mencukupi.");
        }

        return $product;
    }

    /**
     * Membuat Data Transaksi kosong
     *
     * @return Transaction|null
     */
    protected static function generateTemplateTransaction(User $user)
    {
        return Transaction::create([
            'kasir_id' => $user->id,
            'customer_id' => null,
            'invoice' => 'TRX' . date('YmdHis'),
            'date' => date('Y-m-d'),
            'total_price' => 0,
            'total_pay' => 0,
            'total_return' => 0,
        ]);
    }
}
