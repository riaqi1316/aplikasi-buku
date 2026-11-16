<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;

// Transaksi peminjaman dikelola ADMIN
class LoanController extends Controller
{
    public function index(Request $request): View
    {
        $kata = $request->q;

        $loans = Loan::with(['user', 'book'])
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when($kata, fn ($q) => $q->where(function ($w) use ($kata) {
                $w->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$kata}%")
                        ->orWhere('nis', 'like', "%{$kata}%"))
                  ->orWhereHas('book', fn ($b) => $b->where('judul', 'like', "%{$kata}%"));
            }))
            ->latest()->paginate(10)->withQueryString();

        return view('loans.index', compact('loans'));
    }

    public function create(): View
    {
        return view('loans.create', [
            'users' => User::where('role', 'siswa')->orderBy('name')->get(),
            'books' => Book::where('stok', '>', 0)->orderBy('judul')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'book_id' => ['required', 'exists:books,id'],
            'lama' => ['required', 'integer', 'between:1,30'],
        ]);

        try {
            Loan::pinjam(User::findOrFail($data['user_id']), Book::findOrFail($data['book_id']), (int) $data['lama']);
        } catch (RuntimeException $e) {
            return back()->withInput()->withErrors(['book_id' => $e->getMessage()]);
        }

        return redirect()->route('transaksi.index')->with('success', 'Transaksi peminjaman berhasil dicatat.');
    }

    public function edit(Loan $loan): View
    {
        return view('loans.edit', compact('loan'));
    }

    public function update(Request $request, Loan $loan): RedirectResponse
    {
        $data = $request->validate([
            'tanggal_pinjam' => ['required', 'date'],
            'tanggal_jatuh_tempo' => ['required', 'date', 'after_or_equal:tanggal_pinjam'],
        ]);

        $loan->update($data);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function kembalikan(Loan $loan): RedirectResponse
    {
        $loan->kembalikan();

        return back()->with('success', 'Buku berhasil dikembalikan. Denda: Rp '.number_format($loan->denda, 0, ',', '.'));
    }

    public function destroy(Loan $loan): RedirectResponse
    {
        if ($loan->status === 'dipinjam') {
            $loan->book()->increment('stok'); // buku batal dipinjam, stok dikembalikan
        }

        $loan->delete();

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}