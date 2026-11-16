@extends('layouts.app')
@section('title', 'Dashboard Siswa')
@section('content')
<h1>Halo, {{ auth()->user()->name }}</h1>
<div class="grid" style="margin-bottom:20px">
    <div class="stat"><b>{{ $aktif->count() }}</b>Buku sedang dipinjam</div>
    <div class="stat"><b>{{ $totalRiwayat }}</b>Total riwayat peminjaman</div>
</div>
<div class="card">
    <h2 style="margin-top:0;font-size:17px">Buku yang sedang Anda pinjam</h2>
    <table>
        <tr><th>Judul</th><th>Jatuh Tempo</th></tr>
        @forelse ($aktif as $l)
            <tr>
                <td>{{ $l->book->judul }}</td>
                <td>
                    {{ $l->tanggal_jatuh_tempo->format('d-m-Y') }}
                    @if ($l->tanggal_jatuh_tempo->isPast() && ! $l->tanggal_jatuh_tempo->isToday())
                        <span class="badge b-red">Terlambat</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="2" class="muted">Tidak ada buku yang sedang dipinjam.</td></tr>
        @endforelse
    </table>
    <p><a class="btn" href="{{ route('peminjaman.index') }}">Pinjam Buku</a></p>
</div>
@endsection