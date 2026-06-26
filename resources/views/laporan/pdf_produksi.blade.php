<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Produksi UD. SBT</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #1B6B3A; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #1B6B3A; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 11px; color: #666; }
        .summary { width: 100%; margin-bottom: 15px; border-collapse: collapse; }
        .summary td { padding: 8px; background: #f8f9fa; border: 1px solid #ddd; text-align: center; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th { background: #1B6B3A; color: #fff; padding: 6px 8px; font-size: 11px; text-align: left; }
        .table td { padding: 6px 8px; border-bottom: 1px solid #ddd; font-size: 11px; }
        .text-right { text-align: right; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: right; font-size: 10px; color: #888; }
    </style>
</head>
<body>
    <div class="header">
        <h1>UD. SUMBER BAWANG TIMUR</h1>
        <p>Laporan Rekapitulasi Hasil Produksi</p>
        <p>Dicetak Pada: {{ date('d/m/Y H:i') }}</p>
    </div>

    <table class="summary">
        <tr>
            <td><strong>Total Produksi Mentah:</strong><br>{{ number_format($summary['total_produksi'], 0, ',', '.') }} unit</td>
            <td><strong>Total Barang Gagal/Rusak:</strong><br>{{ number_format($summary['total_gagal'], 0, ',', '.') }} unit</td>
            <td><strong>Total Bersih Masuk Stok:</strong><br>{{ number_format($summary['total_bersih'], 0, ',', '.') }} unit</td>
        </tr>
    </table>

    <table class="table">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Kode</th>
                <th>Produk</th>
                <th class="text-right">Produksi</th>
                <th class="text-right">Gagal</th>
                <th class="text-right">Bersih</th>
                <th>Operator</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
            <tr>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_produksi)->format('d/m/Y') }}</td>
                <td>{{ $item->kode_produksi }}</td>
                <td>{{ $item->produk->nama_produk ?? '-' }}</td>
                <td class="text-right">{{ number_format($item->jumlah_produksi, 0, ',', '.') }} {{ $item->satuan->nama_satuan ?? '' }}</td>
                <td class="text-right">{{ number_format($item->jumlah_gagal, 0, ',', '.') }} {{ $item->satuan->nama_satuan ?? '' }}</td>
                <td class="text-right"><strong>{{ number_format($item->jumlah_bersih, 0, ',', '.') }} {{ $item->satuan->nama_satuan ?? '' }}</strong></td>
                <td>{{ $item->karyawan->name ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Halaman ini dihasilkan secara otomatis oleh Sistem Terintegrasi UD. SBT
    </div>
</body>
</html>
