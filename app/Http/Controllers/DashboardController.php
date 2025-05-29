<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Item;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $totalBarang = Item::count();
        $totalUser = User::count();
        $pinjamanHariIni = Borrowing::whereDate('created_at', today())->count();
        $belumDikembalikan = Borrowing::whereIn('status', ['pending', 'approved', 'returned'])->count();

        $borrows = Borrowing::with(['item', 'user'])->latest()->take(6)->get();

        $notifikasiCount = Borrowing::whereIn('status', ['pending', 'returned'])->count();

        $notifikasiList = Borrowing::with('item', 'user')
            ->whereIn('status', ['pending', 'returned'])
            ->latest()
            ->take(5)
            ->get();


        return view('dashboard', compact(
            'totalBarang',
            'totalUser',
            'pinjamanHariIni',
            'belumDikembalikan',
            'borrows',
            'notifikasiCount',
            'notifikasiList'
        ));
    }
}
