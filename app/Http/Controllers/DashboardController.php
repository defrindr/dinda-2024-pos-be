<?php

namespace App\Http\Controllers;

use App\Helpers\CurrencyHelper;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Member;
use App\Models\Product;
use App\Models\Storage;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
  function __construct()
  {
    $this->middleware('auth:api', ['except' => 'reportDownload']);
  }

  public function outOfStock(Request $request)
  {
    $message = 'Data Retrieve';
    $minStock = $request->get('stock', 15);
    $productOutOfStock = Product::where('stock', '<=', $minStock)->limit(10)->get();
    $items = [
      'productOutOfStock' => ProductResource::collection($productOutOfStock),
    ];

    return response()->json(compact('items', 'message'));
  }


  public function bestSelling(Request $request)
  {
    $message = 'Data Retrieve';


    $from_raw = $request->get('from', date('Y-m-d'));
    $to_raw = $request->get('to', date('Y-m-d'));
    $from = date('Y-m-d 00:00:00', strtotime($from_raw));
    $to = date('Y-m-d 23:59:59', strtotime($to_raw));
    $totalTransaction = Transaction::whereBetween('transaction_date', [$from, $to])->sum('total_price');
    $totalTransaction = CurrencyHelper::rupiah($totalTransaction);

    $countTransaction = Transaction::whereBetween('transaction_date', [$from, $to])->count();

    $minStock = $request->get('stock', 15);
    $productOutOfStock = Product::where('stock', '<=', $minStock)->limit(10)->get();

    $productBestSelling = Product::leftJoin('transaction_details', 'products.id', '=', 'transaction_details.product_id')
      ->leftJoin('transactions', 'transactions.id', '=', 'transaction_details.transaction_id')
      ->whereBetween('transaction_date', [$from, $to]) // default 
      ->groupBy(DB::raw('products.id, products.name'))
      ->select(DB::raw('products.id, products.name, sum(transaction_details.amount) as jumlah_terjual'))
      ->limit(10)
      ->get();
    $info = [
      'product' => Product::count(),
      'member' => Member::count(),
      'category' => Category::count(),
      'storage' => Storage::count(),
      'totalTransaction' => $totalTransaction,
      'countTransaction' => $countTransaction
    ];

    $items = [
      'info' => $info,
      'productBestSelling' => $productBestSelling,
    ];

    return response()->json(compact('items', 'message'));
  }

  public function report(Request $request)
  {
    $month = intval($request->get('month')) ? intval($request->get('month')) :  intval(date('m'));
    $year = intval($request->get('year')) ? intval($request->get('year')) : intval(date('Y'));

    $tanggalAwal = "$year-$month-01";
    $tanggalAkhir = date('Y-m-d', strtotime($tanggalAwal . " +1 month -1 day"));

    $laporanBulanan = Transaction::selectRaw('DATE(transaction_date) as transaction_date, SUM(total_price) as total')
      ->whereBetween('transaction_date', [$tanggalAwal, $tanggalAkhir])
      ->groupBy('transaction_date')
      ->orderBy('transaction_date', 'DESC')
      ->get();

    return response()->json(['data' => $laporanBulanan, 'message' => 'retrieve data from ' . $tanggalAwal . ' to ' . $tanggalAkhir]);
  }

  public function reportDownload(Request $request)
  {
    $month = intval($request->get('month')) ? intval($request->get('month')) :  intval(date('m'));
    $year = intval($request->get('year')) ? intval($request->get('year')) : intval(date('Y'));

    $tanggalAwal = "$year-$month-01";
    $tanggalAkhir = date('Y-m-d', strtotime($tanggalAwal . " +1 month -1 day"));

    $laporanBulanan = Transaction::selectRaw('DATE(transaction_date) as transaction_date, SUM(total_price) as total')
      ->whereBetween('transaction_date', [$tanggalAwal, $tanggalAkhir])
      ->groupBy('transaction_date')
      ->orderBy('transaction_date', 'DESC')
      ->get();


    $pdf = Pdf::loadView('includes.report_template', compact('laporanBulanan', 'tanggalAwal', 'tanggalAkhir'))->setOptions([
      'defaultFont' => 'sans-serif',
    ]);

    return $pdf->stream();
  }
}
