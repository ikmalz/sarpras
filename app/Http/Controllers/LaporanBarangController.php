<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LaporanBarangController extends Controller
{
    public function barang()
    {
        $items = Item::with([
            'category',
            'borrows' => function ($query) {
                $query->where('status', 'approved')->with('user');
            }

        ])->orderBy('name')->get();


        $totalStock = $items->sum('stock');

        foreach ($items as $item) {
            if ($item->stock == 0) {
                $this->sendEmptyStockToApi($item);
            }
        }

        return view('laporanBarang.list', compact('items', 'totalStock'));
    }

    private function sendEmptyStockToApi($item)
    {
        \Log::info("Item {$item->name} stok habis, dikirim ke API.");

        // Jika ingin pakai Guzzle atau Http:
        // Http::post('https://api.external.com/notify', [...])
    }
}
