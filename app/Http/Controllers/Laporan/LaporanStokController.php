<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Services\LaporanService;
use App\Services\AuditLogService;
use App\Models\Produk;
use App\Exports\LaporanStokExport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class LaporanStokController extends Controller
{
    public function __construct(protected LaporanService $laporanService, protected AuditLogService $auditLog) {}

    public function index(Request $request)
    {
        $res = $this->laporanService->getLaporanStok($request);
        $produks = Produk::orderBy('nama_produk')->get();

        return view('laporan.stok', array_merge($res, compact('produks')));
    }

    public function exportPdf(Request $request)
    {
        $res = $this->laporanService->getLaporanStok($request);
        $this->auditLog->catat('Laporan', 'export', 'Export PDF Laporan Stok');
        
        $pdf = Pdf::loadView('laporan.pdf_stok', $res);
        return $pdf->download('laporan-stok-' . date('Y-m-d') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $res = $this->laporanService->getLaporanStok($request);
        $this->auditLog->catat('Laporan', 'export', 'Export Excel Laporan Stok');
        
        return Excel::download(new LaporanStokExport($res), 'laporan-stok-' . date('Y-m-d') . '.xlsx');
    }
}
