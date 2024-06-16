<table>
    <tbody>
        <tr>
            <td>Tanggal :</td>
            <td>{{ $tanggal }}</td>
        </tr>
        @if (auth()->user()->role != \App\Models\User::LEVEL_KASIR)
            <tr>
                <td>Laba :</td>
                <td>{{ \App\Helpers\Currencyhelper::rupiah($laba->total) }}</td>
            </tr>
        @endif
    </tbody>
</table>
<table>
    <thead>
        <tr>
            <th>Tgl Transaksi</th>
            <th>Nama Produk</th>
            <th>Qty</th>
            <th>Harga</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($transactions as $detail)
            <tr>
                <td>{{ \App\Helpers\DateHelper::readableDate($detail->transaction->date) }}</td>
                <td>{{ $detail->product->name }}</td>
                <td>{{ $detail->quantity }}</td>
                <td>{{ \App\Helpers\Currencyhelper::rupiah($detail->price) }}</td>
                <td>{{ \App\Helpers\Currencyhelper::rupiah($detail->total_price) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
