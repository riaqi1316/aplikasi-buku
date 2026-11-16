<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;

// Peminjaman & pengembalian oleh SISWA
class StudentLoanController extends Controller
{
    private const MAKS_PINJAM = 3;

    public function index(Request $request): View
    {
        $books = Book::cari($request->q)->where('stok', '>', 0)
            ->orderBy('judul')->paginate(9)->withQueryString();

        return view('student.pinjam', compact('books'));
    }

    public function pinjam(Request $request, Book $book): RedirectResponse
    {
        $user = $request->user();

        if ($user->loans()->where('status', 'dipinjam')->count() >= self::MAKS_PINJAM) {
            return back()->withErrors(['pinjam' => 'Batas peminjaman '.self::MAKS_PINJAM.' buku sudah tercapai. Kembalikan buku terlebih dahulu.']);
        }

        try {
            Loan::pinjam($user, $book, 7);
        } catch (RuntimeException $e) {
            return back()->withErrors(['pinjam' => $e->getMessage()]);
        }

        return redirect()->route('pengembalian.index')
            ->with('success', 'Buku "'.$book->judul.'" berhasil dipinjam selama 7 hari.');
    }

    public function pengembalian(Request $request): View
    {
        $loans = $request->user()->loans()->with('book')->latest()->get();

        return view('student.kembali', compact('loans'));
    }

    public function kembalikan(Request $request, Loan $loan): RedirectResponse
    {
        abort_unless($loan->user_id === $request->user()->id, 403);

        $loan->kembalikan();

        $pesan = 'Buku berhasil dikembalikan.';
        if ($loan->denda > 0) {
            $pesan .= ' Denda keterlambatan: Rp '.number_format($loan->denda, 0, ',', '.');
        }

        return back()->with('success', $pesan);
    }
}