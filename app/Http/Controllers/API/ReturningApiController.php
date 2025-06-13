<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Returnings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReturningApiController extends Controller
{
    public function returnItem(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            'description' => 'nullable|string',
        ], [
            'image.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
            'image.required' => 'Gambar wajib diuggah',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'Format harus berupa jpg, jpeg, atau png.'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $borrowing = Borrowing::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($borrowing->status !== 'approved') {
            return response()->json(['Message' => 'Peminjaman belum disetujui dan tidak dapat dikembalikan'], 400);
        }

        $imagePath = $request->file('image')->store('returnings', 'public');

        $return = Returnings::create([
            'borrowing_id' => $borrowing->id,
            'returned_quantity' => $borrowing->quantity,
            'image' => $imagePath,
            'description' => $request->description,
        ]);

        $borrowing->update([
            'status' => 'returned'
        ]);

        return response()->json([
            'Message' => 'Pengembalian berhasil menunggu persetujuan admin',
            'data' => [
                'id' => $return->id,
                'returned_quantity' => $return->returned_quantity,
                'image' => $return->image,
                'description' => $return->description,
                'created_at' => $return->created_at->format('Y-m-d H:i:s'),
            ]
        ]);
    }

    public function approve($returning_id)
    {
        if (!Auth::user()->can('approve return')) {
            return response()->json(['Message' => 'Akses ditolak. Hanya admin yang dapat menyetujui'], 403);
        }

        $returning = Returnings::find($returning_id);

        if (!$returning) {
            return response()->json(['message' => 'Data pengembalian tidak ditemukan'], 404);
        }

        if ($returning->is_confirmed == true) {
            return response()->json([
                'Message' => 'Pengembalian sudah disetujui sebelumnya'
            ], 400);
        }

        $borrowing = $returning->borrowing;

        if (!in_array($borrowing->status, ['returned'])) {
            return response()->json([
                'Message' => 'Barang belum dikembalikan atau sudah diproses sebelumnya'
            ], 400);
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

        return response()->json([
            'Message' => 'Pengembalian berhasil disetujui dan stock telah diperbarui',
            'data' => [
                'returning' => $returning->load('borrowing.item'),
                'borrowing' => $borrowing,
                'item' => $item
            ]
        ], 200);
    }
}
