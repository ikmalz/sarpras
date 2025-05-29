<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanPeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $borrowings = Borrowing::with(['item', 'user', 'approver', 'returning'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('laporanPeminjaman.list', compact('borrowings'));
    }

    public function exportPdf()
    {
        $borrowings = Borrowing::with(['user', 'item', 'approver'])->get();

        $pdf = Pdf::loadView('laporanPeminjaman.pdf', compact('borrowings'));
        return $pdf->download('laporan_peminjaman.pdf');
    }
}
