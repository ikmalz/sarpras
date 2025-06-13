<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use Illuminate\Http\Request;
use App\Models\Returnings;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Builder;

class LaporanPengembalianController extends Controller
{
    public function index(Request $request)
    {
        $query = Returnings::with(['borrowing.user', 'borrowing.item']);

        if ($search = $request->input('search')) {
            $query->whereHas('borrowing.user', function (Builder $q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $sort = $request->input('sort', 'created_desc');

        if ($request->input('sort') === 'name_asc') {
            $query->join('borrowings', 'returnings.borrowing_id', '=', 'borrowings.id')
                ->join('users', 'borrowings.user_id', '=', 'users.id')
                ->orderBy('users.name', 'asc')
                ->select('returnings.*');
        } elseif ($request->input('sort') === 'name_desc') {
            $query->join('borrowings', 'returnings.borrowing_id', '=', 'borrowings.id')
                ->join('users', 'borrowings.user_id', '=', 'users.id')
                ->orderBy('users.name', 'desc')
                ->select('returnings.*');
        } elseif ($sort === 'created_asc') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc'); 
        }



        $rowsPerPage = $request->input('rows', 5);
        $totalRows = Borrowing::count();
        $returns = $query->paginate($rowsPerPage)->appends($request->query());
        $permisionsAll = Permission::orderBy('name', 'ASC')->get();

        return view('laporanPengembalian.list', [
            'returns' => $returns,
            'totalRows' => $totalRows,
            'permissionAll' => $permisionsAll,
            'selectedSort' => $sort,
        ]);
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
