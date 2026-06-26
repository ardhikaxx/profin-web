<table border="1">
    <thead>
        <tr>
            <th colspan="7" style="font-size: 16px; font-weight: bold; text-align: center;">LAPORAN PRODUKSI — UD. SUMBER BAWANG TIMUR</th>
        </tr>
        <tr>
            <th colspan="7" style="text-align: center;">Dicetak pada: {{ date('d/m/Y H:i') }}</th>
        </tr>
        <tr></tr>
        <tr style="background-color: #1B6B3A; color: #ffffff;">
            <th>Tanggal</th>
            <th>Kode Produksi</th>
            <th>Nama Produk</th>
            <th>Total Produksi</th>
            <th>Barang Gagal/Rusak</th>
            <th>Produksi Bersih</th>
            <th>Operator</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $item)
        <tr>
            <td>{{ \Carbon\Carbon::parse($item->tanggal_produksi)->format('d/m/Y') }}</td>
            <td>{{ $item->kode_produksi }}</td>
            <td>{{ $item->produk->nama_produk ?? '-' }}</td>
            <td>{{ $item->jumlah_produksi }} {{ $item->satuan->nama_satuan ?? '' }}</td>
            <td>{{ $item->jumlah_gagal }} {{ $item->satuan->nama_satuan ?? '' }}</td>
            <td>{{ $item->jumlah_bersih }} {{ $item->satuan->nama_satuan ?? '' }}</td>
            <td>{{ $item->karyawan->name ?? '-' }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="3" style="font-weight: bold; text-align: right;">TOTAL:</td>
            <td style="font-weight: bold;">{{ $summary['total_produksi'] }}</td>
            <td style="font-weight: bold;">{{ $summary['total_gagal'] }}</td>
            <td style="font-weight: bold;">{{ $summary['total_bersih'] }}</td>
            <td></td>
        </tr>
    </tbody>
</table>
