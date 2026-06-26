<table border="1">
    <thead>
        <tr>
            <th colspan="7" style="font-size: 16px; font-weight: bold; text-align: center;">LAPORAN STOK GUDANG — UD. SUMBER BAWANG TIMUR</th>
        </tr>
        <tr>
            <th colspan="7" style="text-align: center;">Per Tanggal: {{ date('d/m/Y H:i') }}</th>
        </tr>
        <tr></tr>
        <tr style="background-color: #1B6B3A; color: #ffffff;">
            <th>No</th>
            <th>Kode Produk</th>
            <th>Nama Produk</th>
            <th>Satuan</th>
            <th>Stok Aktual Tersedia</th>
            <th>Batas Minimum</th>
            <th>Status Kondisi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $key => $item)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $item->produk->kode_produk ?? '-' }}</td>
            <td>{{ $item->produk->nama_produk ?? '-' }}</td>
            <td>{{ $item->produk->satuan->nama_satuan ?? '-' }}</td>
            <td>{{ $item->jumlah_stok }}</td>
            <td>{{ $item->produk->stok_minimum ?? 0 }}</td>
            <td>{{ $item->jumlah_stok <= ($item->produk->stok_minimum ?? 0) ? 'KRITIS' : 'AMAN' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
