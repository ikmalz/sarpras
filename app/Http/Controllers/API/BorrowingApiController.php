<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\EmergencyRequest;
use App\Models\Item;
use App\Models\Returnings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class BorrowingApiController extends Controller
{
    public function index()
    {
        $borrowings = Borrowing::with(['item', 'returning', 'approver'])->where('user_id', Auth::id())->get();
        return response()->json($borrowings);
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'due' => 'nullable|date|after_or_equal:now'
        ]);

        $item = Item::findOrFail($request->item_id);
        if ($item->stock < $request->quantity) {
            return response()->json([
                'message' => 'Stock tidak mencukupi'
            ], 400);
        }

        $approved_at = $request->status === 'approved' ? now() : null;

        $borrowing = Borrowing::create([
            'item_id' => $item->id,
            'user_id' => Auth::id(),
            'quantity' => $request->quantity,
            'status' => 'pending',
            'approved_at' => $approved_at,
            'due' => $request->due
        ]);

        return response()->json([
            'message' => 'Berhasil mengajukan peminjaman',
            'data' => $borrowing
        ]);
    }

    public function approve($id)
    {
        if (!Auth::user()->can('approve borrowing')) {
            return response()->json(['Message' => 'Akses ditolak. Hanya admin yang dapat menyetujui'], 403);
        }

        $borrowing = Borrowing::findOrFail($id);

        $invalidStatuses = ['approved', 'returned', 'completed', 'rejected'];

        if (in_array($borrowing->status, $invalidStatuses)) {
            return response()->json([
                'message' => 'Peminjaman tidak dapat disetujui karena sudah diproses sebelumnya'
            ], 400);
        }

        $activeCount = Borrowing::where('user_id', $borrowing->user_id)
            ->whereIn('status', ['approved', 'returned'])
            ->count();

        if ($activeCount >= 3) {
            return response()->json([
                'message' => 'Pengguna telah mencapai batas maksimum 3 peminjaman aktif'
            ], 400);
        }

        $item = $borrowing->item;

        if ($item->stock < $borrowing->quantity) {
            return response()->json([
                'message' => 'Stok tidak mencukupi untuk menyetujui peminjaman'
            ], 400);
        }

        $item->stock -= $borrowing->quantity;
        $item->save();

        $borrowing->approved_by = Auth::id();
        $borrowing->status = 'approved';
        $borrowing->approved_at = now();
        $borrowing->due = now()->addDays(); 
        $borrowing->save();

        return response()->json([
            'message' => 'Peminjaman berhasil disetujui',
            'data' => $borrowing
        ]);
    }


    public function reject($id)
    {
        $borrowing = Borrowing::findOrFail($id);

        if ($borrowing->status !== 'pending') {
            return response()->json([
                'Message' => 'Peminjaman tidak dapat ditolak karena sudah diproses sebelumnya'
            ], 400);
        }

        $borrowing->status = 'rejected';
        $borrowing->approved_by = Auth::id();
        $borrowing->approved_at = now();
        $borrowing->save();

        return response()->json([
            'message' => 'Peminjaman berhasil ditolak',
            'data' => $borrowing
        ]);
    }
}
