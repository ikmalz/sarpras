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

        return view('dashboard', compact(
            'totalBarang',
            'totalUser',
            'pinjamanHariIni',
            'belumDikembalikan',
            'borrows'
        ));
    }
}
