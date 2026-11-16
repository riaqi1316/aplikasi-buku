@extends('layouts.app')
@section('title', 'Data Buku')
@section('content')
<h1>Data Buku</h1>
<div class="card">
    <form class="toolbar" method="GET" action="{{ route('buku.index') }}">
        <input name="q" value="{{ request('q') }}" placeholder="Cari kode, judul, penulis, atau kategori...">
        <button class="btn" type="submit">Cari</button>
        @if (auth()->user()->isAdmin())
            <a class="btn btn-green" href="{{ route('buku.create') }}">+ Tambah Buku</a>
        @endif
    </form>

    <table>
        <tr>
            <th>Kode</th><th>Judul</th><th>Penulis</th><th>Penerbit</th><th>Tahun</th><th>Kategori</th><th>Stok</th>
            @if (auth()->user()->isAdmin())<th>Aksi</th>@endif
        </tr>
        @forelse ($books as $b)
            <tr>
                <td>{{ $b->kode }}</td>
                <td>{{ $b->judul }}</td>
                <td>{{ $b->penulis }}</td>
                <td>{{ $b->penerbit }}</td>
                <td>{{ $b->tahun }}</td>
                <td>{{ $b->kategori }}</td>
                <td>{{ $b->stok }}</td>
                @if (auth()->user()->isAdmin())
                    <td>
                        <a class="btn btn-sm" href="{{ route('buku.edit', $b) }}">Edit</a>
                        <form class="inline" method="POST" action="{{ route('buku.destroy', $b) }}"
                              onsubmit="return confirm('Hapus buku ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-red" type="submit">Hapus</button>
                        </form>
                    </td>
                @endif
            </tr>
        @empty
            <tr><td colspan="8" class="muted">Data buku tidak ditemukan.</td></tr>
        @endforelse
    </table>
    {{ $books->links('pagination::bootstrap-4') }}
</div>
@endsection