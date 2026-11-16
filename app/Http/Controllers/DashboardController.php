<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return view('dashboard.admin', [
                'totalBuku' => Book::count(),
                'totalAnggota' => User::where('role', 'siswa')->count(),
                'sedangDipinjam' => Loan::where('status', 'dipinjam')->count(),
                'terlambat' => Loan::where('status', 'dipinjam')
                    ->whereDate('tanggal_jatuh_tempo', '<', today())->count(),
                'terbaru' => Loan::with(['user', 'book'])->latest()->take(5)->get(),
            ]);
        }

        return view('dashboard.siswa', [
            'aktif' => $user->loans()->with('book')->where('status', 'dipinjam')->get(),
            'totalRiwayat' => $user->loans()->count(),
        ]);
    }
}