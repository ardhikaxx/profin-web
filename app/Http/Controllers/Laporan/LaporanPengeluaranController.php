<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Services\LaporanService;
use App\Services\AuditLogService;
use App\Models\KategoriPengeluaran;
use App\Exports\LaporanPengeluaranExport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class LaporanPengeluaranController extends Controller
{
    public function __construct(protected LaporanService $laporanService, protected AuditLogService $auditLog) {}

    public function index(Request $request)
    {
        $res = $this->laporanService->getLaporanPengeluaran($request);
        $kategoris = KategoriPengeluaran::orderBy('nama_kategori')->get();

        return view('laporan.pengeluaran', array_merge($res, compact('kategoris')));
    }

    public function exportPdf(Request $request)
    {
        $res = $this->laporanService->getLaporanPengeluaran($request);
        $this->auditLog->catat('Laporan', 'export', 'Export PDF Laporan Pengeluaran');
        
        $pdf = Pdf::loadView('laporan.pdf_pengeluaran', $res);
        return $pdf->download('laporan-pengeluaran-' . date('Y-m-d') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $res = $this->laporanService->getLaporanPengeluaran($request);
        $this->auditLog->catat('Laporan', 'export', 'Export Excel Laporan Pengeluaran');
        
        return Excel::download(new LaporanPengeluaranExport($res), 'laporan-pengeluaran-' . date('Y-m-d') . '.xlsx');
    }
}
