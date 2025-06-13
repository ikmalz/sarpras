<?php

namespace App\Http\Controllers;
use App\Exports\StockExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ReportExcelController extends Controller
{
    public function exportExcel()
    {
        return Excel::download(new StockExport, 'laporan_stok_barang.xlsx');
    }
}
