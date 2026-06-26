<table border="1">
    <thead>
        <tr>
            <th colspan="6" style="font-size: 16px; font-weight: bold; text-align: center;">LAPORAN PENGELUARAN — UD. SUMBER BAWANG TIMUR</th>
        </tr>
        <tr>
            <th colspan="6" style="text-align: center;">Dicetak: {{ date('d/m/Y H:i') }}</th>
        </tr>
        <tr></tr>
        <tr style="background-color: #DC3545; color: #ffffff;">
            <th>Tanggal</th>
            <th>Kode Transaksi</th>
            <th>Kategori Biaya</th>
            <th>Keterangan</th>
            <th>Nominal (Rp)</th>
            <th>Operator</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $item)
        <tr>
            <td>{{ \Carbon\Carbon::parse($item->tanggal_pengeluaran)->format('d/m/Y') }}</td>
            <td>{{ $item->kode_transaksi }}</td>
            <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>
            <td>{{ $item->keterangan }}</td>
            <td>{{ $item->jumlah }}</td>
            <td>{{ $item->karyawan->name ?? '-' }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="4" style="font-weight: bold; text-align: right;">TOTAL PENGELUARAN:</td>
            <td style="font-weight: bold;">{{ $summary['total_pengeluaran'] }}</td>
            <td></td>
        </tr>
    </tbody>
</table>
