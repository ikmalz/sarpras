<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BorrowingController extends Controller
{
    public function index(Request $request)
    {
        $query = Borrowing::query();

        if ($request->has('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        switch ($request->sort) {
            case 'name_asc':
                $query->orderBy(User::select('name')->whereColumn('users.id', 'borrowings.user_id'), 'asc');
                break;
            case 'name_desc':
                $query->orderBy(User::select('name')->whereColumn('users.id', 'borrowings.user_id'), 'desc');
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

        $query->with('item', 'user');

        $rowsPerPage = $request->input('rows', 5);

        $borrowings = $query->paginate($rowsPerPage);

        $totalBorrowing = $query->count();

        return view('borrow.list', [
            'borrowings' => $borrowings,
            'totalBorrowing' => $totalBorrowing
        ]);
    }

    public function approve($id)
    {
        $borrowing = Borrowing::findOrFail($id);

        if (!Auth::user()->can('approve borrowing')) {
            return back()->with('error', 'Akses ditolak');
        }

        if (in_array($borrowing->status, ['approved', 'returned', 'completed', 'rejected'])) {
            return back()->with('error', 'Peminjaman sudah diproses sebelumnya');
        }

        if ($borrowing->item->stock < $borrowing->quantity) {
            return back()->with('error', 'Stock tidak mencukupi');
        }

        $borrowing->item->decrement('stock', $borrowing->quantity);

        if ($borrowing->status != 'pending') return back()->with('error', 'Sudah diproses');

        $borrowing->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
            'due' => now()->addMinutes(30),
        ]);

        return back()->with('succes', 'Peminjaman disetujui');
    }

    public function reject($id)
    {
        $borrowing = Borrowing::findOrFail($id);

        if (!Auth::user()->can('approve borrowing')) {
            return back()->with('error', 'Akses ditolak');
        }

        if ($borrowing->status !== 'pending') {
            return back()->with(
                'error',
                'Peminjaman tidak dapat ditolak karena sudah diproses sebelumnya'
            );
        }

        $borrowing->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return back()->with(
            'success',
            'Peminjaman berhasil ditolak'
        );
    }

    public function confirmReturn($borrowingId)
    {

        $borrowing = Borrowing::with('returning')->findOrFail($borrowingId);

        if ($borrowing->status !== 'returned') {
            return back()->with('error', 'Belum dikembalikan');
        }

        $borrowing->returning->update([
            'isConfirmed' => true,
            'handled_by' => Auth::id()
        ]);

        $borrowing->item->increment('stock', $borrowing->quantity);

        $borrowing->update([
            'status' => 'completed'
        ]);

        return back()->with('succes', 'Pengembalian dikonfirmasi');
    }
}
