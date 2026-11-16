@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('content')
<h1>Dashboard Admin</h1>
<div class="grid" style="margin-bottom:20px">
    <div class="stat"><b>{{ $totalBuku }}</b>Judul buku</div>
    <div class="stat"><b>{{ $totalAnggota }}</b>Anggota (siswa)</div>
    <div class="stat"><b>{{ $sedangDipinjam }}</b>Sedang dipinjam</div>
    <div class="stat"><b>{{ $terlambat }}</b>Terlambat</div>
</div>
<div class="card">
    <h2 style="margin-top:0;font-size:17px">Transaksi Terbaru</h2>
    <table>
        <tr><th>Anggota</th><th>Buku</th><th>Tgl Pinjam</th><th>Status</th></tr>
        @forelse ($terbaru as $t)
            <tr>
                <td>{{ $t->user->name }}</td>
                <td>{{ $t->book->judul }}</td>
                <td>{{ $t->tanggal_pinjam->format('d-m-Y') }}</td>
                <td>{{ $t->status }}</td>
            </tr>
        @empty
            <tr><td colspan="4" class="muted">Belum ada transaksi.</td></tr>
        @endforelse
    </table>
</div>
@endsection