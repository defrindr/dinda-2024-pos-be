<?php

namespace App\Http\Controllers;

use App\Http\Resources\TransactionCollection;
use App\Http\Resources\TransactionResource;
use App\Models\Member;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
  function __construct()
  {
    $this->middleware('auth:api', ['except' => ['download_invoice']]);
  }

  public function index(Request $request)
  {
    $pagination = Transaction::orderBy('id', 'desc')->search($request->get('search'))->paginate(10);
    return response()->json(new TransactionCollection($pagination));
  }

  public function show(Transaction $transaction)
  {
    return response()->json(['data' => new TransactionResource($transaction), 'message' => 'Data ditemukan']);
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'items.*.product_id' => 'required|numeric',
      'items.*.amount' => 'required|numeric',

      'member_id' => 'numeric',
      'discount' => 'nullable|numeric',
      'pay' => 'required|numeric',
    ]);

    if ($validator->fails()) {
      return response()->json(['message' => $validator->messages()->first()], 400);
    }

    DB::beginTransaction();
    try {

      $transaction = Transaction::create([
        'transaction_code' => 'TRX' . date('YmdHis'),
        'transaction_date' => date('Y-m-d'),
        'member_id' => null,
        'price' => 0,
        'discount' => 0,
        'total_price' => 0,
        'pay' => 0,
        'back' => 0,
      ]);

      $totalPurchase = 0;
      $totalPrice = 0;
      $pay = $request->get('pay');
      $discount = $request->get('discount') ?? 0;
      $memberId = $request->get('member_id');
      $items = $request->get('items');

      foreach ($items as $item) {
        $productId = $item['product_id'];
        $productAmount = $item['amount'];
        $product = Product::where('id', $productId)->first();

        if (!$product) {
          DB::rollBack();
          return response()->json(['message' => 'Product #' . $productId . ' doesnt exist'], 400);
        }

        // check stock
        if ($product->stock < $productAmount) {
          DB::rollBack();
          return response()->json(['message' => 'Insufficient quantity of ' . $product->name], 400);
        }

        $productTotalPrice = $product->price * $productAmount;

        TransactionDetail::create([
          'transaction_id' => $transaction->id,
          'product_id' => $product->id,
          'price' => $product->price,
          'amount' => $productAmount,
          'total_price' => $productTotalPrice
        ]);

        $totalPurchase += $productTotalPrice;

        $product->update(['stock' => $product->stock - $productAmount]);
      }

      if ($memberId) {
        $member = Member::where('id', $memberId)->first();
        if (!$member) {
          DB::rollBack();
          return response()->json(['message' => 'Member doesnt regiested in our system'], 400);
        }
      }

      $totalPrice = $totalPurchase - $discount;
      if ($totalPrice > $pay) {
        DB::rollBack();
        return response()->json(['message' => 'Minimum pay is ' . $totalPrice], 400);
      }

      $changeMoney = $pay - $totalPrice;

      $transaction->update([
        'total_price' => $totalPrice,
        'price' => $totalPurchase,
        'discount' => $discount,
        'pay' => $pay,
        'back' => $changeMoney,
        'member_id' => $memberId
      ]);

      DB::commit();
      return response()->json(['message' => 'Transaction berhasil dibuat'], 200);
    } catch (\Throwable $th) {
      DB::rollBack();
      Log::error($th->getMessage());
      return response()->json(['message' => 'Terjadi kesalahan saat menjalankan aksi'], 400);
    }
  }

  function download_invoice($oid)
  {
    $transaction  = Transaction::where('transaction_code', $oid)->first();
    if (!$transaction) return response()->json(['message' => 'Transaksi tidak ditemukan'], 400);
    $invoice_date = date('jS F Y', strtotime($transaction->transaction_date));
    $pdf          = Pdf::loadView('includes.invoice_template', compact('transaction'))->setOptions([
      'defaultFont' => 'sans-serif',
    ])->setPaper([0, 0, 300, 460], 'portrait');

    return $pdf->stream(); //('Invoice_' . config('app.name') . '_Order_No # ' . $oid . ' Date_' . $invoice_date . '.pdf');
  }


  public function destroy(Transaction $transaction)
  {
    try {
      $transaction->delete();
      return response()->json(['message' => 'Transaction berhasil dihapus'], 200);
    } catch (\Throwable $th) {
      Log::error($th->getMessage());
      return response()->json(['message' => 'Terjadi kesalahan saat menjalankan aksi'], 400);
    }
  }
}
