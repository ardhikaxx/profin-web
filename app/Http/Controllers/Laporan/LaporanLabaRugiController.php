<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Services\LaporanService;
use App\Services\AuditLogService;
use App\Exports\LaporanLabaRugiExport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class LaporanLabaRugiController extends Controller
{
    public function __construct(protected LaporanService $laporanService, protected AuditLogService $auditLog) {}

    public function index(Request $request)
    {
        $res = $this->laporanService->getLaporanLabaRugi($request);

        return view('laporan.laba_rugi', $res);
    }

    public function exportPdf(Request $request)
    {
        $res = $this->laporanService->getLaporanLabaRugi($request);
        $this->auditLog->catat('Laporan', 'export', 'Export PDF Laporan Laba Rugi');
        
        $pdf = Pdf::loadView('laporan.pdf_laba_rugi', $res);
        return $pdf->download('laporan-laba-rugi-' . date('Y-m-d') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $res = $this->laporanService->getLaporanLabaRugi($request);
        $this->auditLog->catat('Laporan', 'export', 'Export Excel Laporan Laba Rugi');
        
        return Excel::download(new LaporanLabaRugiExport($res), 'laporan-laba-rugi-' . date('Y-m-d') . '.xlsx');
    }
}
