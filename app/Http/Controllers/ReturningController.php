<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Returnings;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReturningController extends Controller
{
    public function index(Request $request)
    {
        $query = Returnings::query();

        $query->whereHas('borrowing.user', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%');
        });

        $query->join('borrowings', 'returnings.borrowing_id', '=', 'borrowings.id')
            ->join('users', 'borrowings.user_id', '=', 'users.id')
            ->select('returnings.*');


        switch ($request->sort) {
            case 'name_asc':
                $query->orderBy('users.name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('users.name', 'desc');
                break;
            case 'created_asc':
                $query->orderBy('returnings.created_at', 'asc');
                break;
            case 'created_desc':
                $query->orderBy('returnings.created_at', 'desc');
                break;
            default:
                $query->orderBy('returnings.created_at', 'desc');
        }


        $query->with('borrowing.user', 'borrowing.item');

        $rowsPerPage = $request->input('rows', 5);

        $returns = $query->paginate($rowsPerPage);

        $totalReturning = Returnings::count();

        return view('return.list', [
            'returns' => $returns,
            'totalReturning' => $totalReturning
        ]);
    }

    public function approve($id)
    {
        $user = auth()->user();

        if (!$user->can('approve return')) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $returning = Returnings::findOrFail($id);

        if ($returning->is_confirmed) {
            return redirect()->back()->with('warning', 'Pengembalian sudah disetujui sebelumnya.');
        }

        $borrowing = $returning->borrowing;

        if (!in_array($borrowing->status, ['returned'])) {
            return redirect()->back()->with(
                'error',
                'Barang belum bisa dikembalikan atau sudah diproses'
            );
        }

        $item = $borrowing->item;

        if ($returning->returned_quantity >= $borrowing->quantity) {
            $borrowing->update(['status' => 'completed']);
        }

        $item->stock += $returning->returned_quantity;
        $item->save();

        $returning->update([
            'is_confirmed' => true,
            'handled_by' => Auth::id()
        ]);

        $completedReturns = \App\Models\Borrowing::where('user_id', $borrowing->user_id)
            ->where('status', 'completed')
            ->count();

        $activeBorrowings = \App\Models\Borrowing::where('user_id', $borrowing->user_id)
            ->whereIn('status', ['pending', 'approved', 'returned'])
            ->count();

        if ($completedReturns > 3 && $activeBorrowings === 0) {
            $borrowing->user->revokePermissionTo('emergency_borrow');
        }

        return redirect()->back()->with('success', 'Pengembalian berhasil disetujui.');
    }
}
