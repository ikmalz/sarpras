<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Returnings;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanPengembalianController extends Controller
{
    public function index()
    {
        $returns = Returnings::with(['borrowing.user', 'borrowing.item', 'handler'])
            ->latest()
            ->get();

        return view("laporanPengembalian.list", compact('returns'));
    }

    public function export()
    {
        $returns = Returnings::with(['borrowing.user', 'borrowing.item', 'handler'])
            ->latest()
            ->get();

        $pdf = Pdf::loadView('laporanPengembalian.export', compact('returns'));
        return $pdf->download('laporan_pengembalian.pdf');
    }
}
