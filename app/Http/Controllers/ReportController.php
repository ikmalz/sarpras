<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function stockReport(Request $request)
    {
        $items = Item::with('category')->orderBy('name')->get();
        $totalStock = $items->sum('stock');

        return view('reports.stock', compact('items', 'totalStock'));
    }

    public function stockReportPdf()
    {
        $items = Item::with('category')->orderBy('name')->get();
        $totalStock = $items->sum('stock');

        $pdf = Pdf::loadView('reports.stock_pdf', compact('items', 'totalStock'));
        return $pdf->download('laporan_stok_barang.pdf');
    }
}
