<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class LaporanBarangController extends Controller
{
    public function barang(Request $request)
    {

        $items = Item::with([
            'category',
            'borrows' => function ($query) {
                $query->where('status', 'approved')->with('user');
            }

        ])->orderBy('name')->get();

        $query = Item::with([
            'category',
            'borrows' => function ($query) {
                $query->where('status', 'approved')->with('user');
            }
        ]);

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        switch ($request->sort) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'created_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case '$created_desc':
                $query->orderBy('created_at', 'desc');
                break;
            default;
                $query->orderBy('created_at', 'desc');
        }

        $totalStockAllItems = $query->clone()->sum('stock');
        $rowsPerPage = $request->get('rows', 5);
        $items = $query->paginate($rowsPerPage)->appends($request->all());

        $totalItem = $query->count();

        foreach ($items as $item) {
            if ($item->stock == 0) {
                $this->sendEmptyStockToApi($item);
            }
        }

        return view('laporanBarang.list', [
            'items' => $items,
            'totalStock' => $totalStockAllItems,
            'totalItem' => $totalItem
        ]);
    }

    private function sendEmptyStockToApi($item)
    {
        Log::info("Item dengan stok kosong terdeteksi", [
            'id' => $item->id,
            'name' => $item->name,
            'category' => optional($item->category)->name,
            'timestamp' => now()
        ]);
    }
}
