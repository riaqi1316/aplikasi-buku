@extends('layouts.app')
@section('title', 'Pengembalian Buku')
@section('content')
<h1>Pengembalian Buku</h1>
<div class="card">
    <table>
        <tr><th>Buku</th><th>Pinjam</th><th>Jatuh Tempo</th><th>Kembali</th><th>Denda</th><th>Status</th><th>Aksi</th></tr>
        @forelse ($loans as $l)
            <tr>
                <td>{{ $l->book->judul }}</td>
                <td>{{ $l->tanggal_pinjam->format('d-m-Y') }}</td>
                <td>{{ $l->tanggal_jatuh_tempo->format('d-m-Y') }}</td>
                <td>{{ $l->tanggal_kembali?->format('d-m-Y') ?? '-' }}</td>
                <td>Rp {{ number_format($l->denda, 0, ',', '.') }}</td>
                <td><span class="badge {{ $l->status === 'dipinjam' ? 'b-blue' : 'b-green' }}">{{ $l->status }}</span></td>
                <td>
                    @if ($l->status === 'dipinjam')
                        <form class="inline" method="POST" action="{{ route('pengembalian.kembali', $l) }}">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm btn-green" type="submit">Kembalikan</button>
                        </form>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="muted">Anda belum pernah meminjam buku.</td></tr>
        @endforelse
    </table>
</div>
@endsection