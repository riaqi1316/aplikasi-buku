<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BookController extends Controller
{
    private function rules(?Book $book = null): array
    {
        return [
            'kode' => ['required', 'string', 'max:20', Rule::unique('books', 'kode')->ignore($book?->id)],
            'judul' => ['required', 'string', 'max:255'],
            'penulis' => ['required', 'string', 'max:255'],
            'penerbit' => ['required', 'string', 'max:255'],
            'tahun' => ['required', 'integer', 'between:1900,'.date('Y')],
            'kategori' => ['required', 'string', 'max:50'],
            'stok' => ['required', 'integer', 'min:0'],
        ];
    }

    public function index(Request $request): View
    {
        $books = Book::cari($request->q)->orderBy('judul')->paginate(10)->withQueryString();

        return view('books.index', compact('books'));
    }

    public function create(): View
    {
        return view('books.form', ['book' => new Book]);
    }

    public function store(Request $request): RedirectResponse
    {
        Book::create($request->validate($this->rules()));

        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function edit(Book $book): View
    {
        return view('books.form', compact('book'));
    }

    public function update(Request $request, Book $book): RedirectResponse
    {
        $book->update($request->validate($this->rules($book)));

        return redirect()->route('buku.index')->with('success', 'Data buku berhasil diperbarui.');
    }

    public function destroy(Book $book): RedirectResponse
    {
        if ($book->loans()->exists()) {
            return back()->withErrors(['hapus' => 'Buku tidak dapat dihapus karena sudah memiliki riwayat peminjaman.']);
        }

        $book->delete();

        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus.');
    }
}