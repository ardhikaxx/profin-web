<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Services\LaporanService;
use App\Services\AuditLogService;
use App\Models\Produk;
use App\Models\User;
use App\Exports\LaporanProduksiExport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class LaporanProduksiController extends Controller
{
    public function __construct(protected LaporanService $laporanService, protected AuditLogService $auditLog) {}

    public function index(Request $request)
    {
        $res = $this->laporanService->getLaporanProduksi($request);
        $produks   = Produk::orderBy('nama_produk')->get();
        $karyawans = User::where('role', 'karyawan')->orderBy('name')->get();

        return view('laporan.produksi', array_merge($res, compact('produks', 'karyawans')));
    }

    public function exportPdf(Request $request)
    {
        $res = $this->laporanService->getLaporanProduksi($request);
        $this->auditLog->catat('Laporan', 'export', 'Export PDF Laporan Produksi');
        
        $pdf = Pdf::loadView('laporan.pdf_produksi', $res);
        return $pdf->download('laporan-produksi-' . date('Y-m-d') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $res = $this->laporanService->getLaporanProduksi($request);
        $this->auditLog->catat('Laporan', 'export', 'Export Excel Laporan Produksi');
        
        return Excel::download(new LaporanProduksiExport($res), 'laporan-produksi-' . date('Y-m-d') . '.xlsx');
    }
}
