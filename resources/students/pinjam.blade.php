@extends('layouts.app')
@section('title', 'Peminjaman Buku')
@section('content')
<h1>Peminjaman Buku</h1>
<form class="toolbar" method="GET" action="{{ route('peminjaman.index') }}">
    <input name="q" value="{{ request('q') }}" placeholder="Cari judul, penulis, atau kategori...">
    <button class="btn" type="submit">Cari</button>
</form>

<div class="book-grid">
    @forelse ($books as $b)
        <div class="book-card">
            <h3>{{ $b->judul }}</h3>
            <div class="muted">{{ $b->penulis }} &middot; {{ $b->penerbit }} ({{ $b->tahun }})</div>
            <p><span class="badge b-gray">{{ $b->kategori }}</span> Stok: <b>{{ $b->stok }}</b></p>
            <form method="POST" action="{{ route('peminjaman.pinjam', $b) }}">
                @csrf
                <button class="btn btn-green" type="submit">Pinjam (7 hari)</button>
            </form>
        </div>
    @empty
        <p class="muted">Tidak ada buku yang tersedia.</p>
    @endforelse
</div>
{{ $books->links('pagination::bootstrap-4') }}
@endsection