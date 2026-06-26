<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pengeluaran UD. SBT</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #dc3545; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #dc3545; font-size: 18px; }
        .summary { margin-bottom: 15px; padding: 10px; background: #fff5f5; border: 1px solid #feb2b2; text-align: center; font-size: 14px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th { background: #dc3545; color: #fff; padding: 6px 8px; font-size: 11px; text-align: left; }
        .table td { padding: 6px 8px; border-bottom: 1px solid #ddd; font-size: 11px; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>UD. SUMBER BAWANG TIMUR</h1>
        <p>Laporan Rekapitulasi Biaya Operasional</p>
        <p>Dicetak: {{ date('d/m/Y H:i') }}</p>
    </div>

    <div class="summary">
        <strong>Total Pengeluaran:</strong> Rp {{ number_format($summary['total_pengeluaran'], 0, ',', '.') }}
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Kode</th>
                <th>Kategori</th>
                <th>Keterangan</th>
                <th class="text-right">Nominal (Rp)</th>
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
                <td class="text-right">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                <td>{{ $item->karyawan->name ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
