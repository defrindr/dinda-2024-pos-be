<?php

use App\Helpers\CurrencyHelper;

$setting = \App\Models\ProfilAplikasi::first();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Transaksi</title>
    <style>
        * {
            font-family: 'Courier New', Courier, monospace;
        }

        .dotted {
            border-bottom: 2px dotted #000;
            width: 100%;
            margin-top: .5rem;
            margin-bottom: .5rem;
        }

        @page {
            margin-top: 0px;
            margin-bottom: 0px;
        }
    </style>
</head>

<body>
    <h1 style="text-align: center;">{{ $setting->nama_aplikasi }}</h1>
    <p style="text-align: center;margin-bottom: 1rem">{{ $setting->alamat }}</p>
    <div class="dotted"></div>
    <table>
        <tr>
            <td>No Transaksi</td>
            <td>:</td>
            <td>{{ $transaction->invoice }}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>:</td>
            <td>{{ date('d F Y', strtotime($transaction->date)) }}</td>
        </tr>
        <tr>
            <td>Member</td>
            <td>:</td>
            <td>{{ $transaction->member ? $transaction->pelanggan->name : '-' }}</td>
        </tr>
    </table>
    <div class="dotted"></div>
    <table style="width: 100%; font-size: 9pt">
        @foreach ($transaction->items as $item)
            <tr>
                <td style="width: 50%;font-weight: bold"><?= $item->product->name ?></td>
                <td style="width: 50%;text-align:end"></td>
            </tr>
            <tr>
                <td style="width: 50%;"><?= $item->quantity ?> X <?= CurrencyHelper::rupiah($item->price) ?></td>
                <td style="width: 50%;text-align:right">{{ CurrencyHelper::rupiah($item->total_price) }}</td>
            </tr>
        @endforeach
    </table>
    <div class="dotted"></div>
    <table style="width: 100%; font-size: 9pt">
        <tr>
            <td style="width: 50%;font-weight:bold">Total Harga</td>
            <td style="width: 50%;text-align:right">{{ CurrencyHelper::rupiah($transaction->total_price) }}</td>
        </tr>
        <tr>
            <td style="width: 50%;font-weight:bold">Jumlah Bayar</td>
            <td style="width: 50%;text-align:right">{{ CurrencyHelper::rupiah($transaction->total_pay) }}</td>
        </tr>
        <tr>
            <td style="width: 50%;font-weight:bold">Kembalian</td>
            <td style="width: 50%;text-align:right">{{ CurrencyHelper::rupiah($transaction->total_return) }}</td>
        </tr>
    </table>
    <p style="margin: auto;display:block;margin-top:1rem;text-align:center">~Terimakasih ~</p>
</body>

</html>
