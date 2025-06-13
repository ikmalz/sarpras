<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Builder;

class LaporanPeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $query = Borrowing::with(['item', 'user', 'approver', 'returning']);

        $baseQuery = Borrowing::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($q2) use ($search) {
                    $q2->where('name', 'like', '%' . $search . '%');
                })->orWhereHas('item', function ($q2) use ($search) {
                    $q2->where('name', 'like', '%' . $search . '%');
                });
            });
        }

        $totalRows = (clone $baseQuery)->count();

        $query = $baseQuery->with(['item', 'user', 'approver', 'returning']);

        $sort = $request->input('sort');
        switch ($sort) {
            case 'name_asc':
                $query->whereHas('user')
                    ->with(['user' => function ($q) {
                        $q->orderBy('name', 'asc');
                    }])
                    ->orderBy(User::select('name')->whereColumn('users.id', 'borrowings.user_id'));
                break;

            case 'name_desc':
                $query->join('users', 'borrowings.user_id', '=', 'users.id')
                    ->orderBy('users.name', 'desc')
                    ->select('borrowings.*');
                break;
            case 'created_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'created_desc':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }


        $rowsPerPage = intval($request->input('rows', 5));
        $rowsPerPage = $rowsPerPage > 0 ? $rowsPerPage : 5;
        $borrowings = $query->paginate($rowsPerPage)->appends($request->query());

        return view('laporanPeminjaman.list', [
            'borrowings' => $borrowings,
            'totalRows' => $totalRows
        ]);
    }

    public function exportPdf()
    {
        $borrowings = Borrowing::with(['user', 'item', 'approver'])->get();

        $pdf = Pdf::loadView('laporanPeminjaman.pdf', compact('borrowings'));
        return $pdf->download('laporan_peminjaman.pdf');
    }
}
