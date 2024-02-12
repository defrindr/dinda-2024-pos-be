<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\TrasanctionRequest;
use App\Http\Resources\TransactionResource;
use App\Http\Services\TransactionService;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['downloadInvoice']]);
    }

    /**
     * Menampilkan list transaksi dengan paginasi
     */
    public function index(Request $request): JsonResponse
    {
        return ResponseHelper::successWithData(
            TransactionService::paginate($request),
            'Transaksi berhasil ditemukan'
        );
    }

    /**
     * Menampilkan detail transaksi
     */
    public function show(Transaction $transaction): JsonResponse
    {
        return ResponseHelper::successWithData(
            new TransactionResource($transaction),
            'Transaksi berhasil ditemukan'
        );
    }

    /**
     * Menambahkan transaksi baru
     */
    public function store(TrasanctionRequest $request): JsonResponse
    {
        try {
            $cashier = auth()->user();
            $success = TransactionService::create($request, $cashier);

            if ($success) {
                return ResponseHelper::successWithData(null, 'Transaksi berhasil dibuat');
            } else {
                return ResponseHelper::badRequest('Transaksi gagal dibuat');
            }
        } catch (\Throwable $th) {
            // log error and return information about error
            Log::error($th->getMessage());

            return ResponseHelper::error($th, 'Terjadi kesalahan saat menjalankan aksi');
        }
    }

    /**
     * Mengunduh detail transaksi berdasarkan kode invoice
     */
    public function downloadInvoice(string $invoiceCode): Response|JsonResponse
    {
        $view = TransactionService::downloadInvoice($invoiceCode);
        if (! $view) {
            return ResponseHelper::badRequest('Transaksi tidak ditemukan');
        }

        return $view->stream();
    }

    /**
     * Menghapus transaksi dan rollback data produk
     */
    public function destroy(Transaction $transaction): JsonResponse
    {
        $cashier = auth()->user();
        try {
            TransactionService::cancelTransaction($transaction, $cashier);

            return response()->json(['message' => 'Transaction berhasil dihapus'], 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response()->json(['message' => 'Terjadi kesalahan saat menjalankan aksi'], 400);
        }
    }
}
