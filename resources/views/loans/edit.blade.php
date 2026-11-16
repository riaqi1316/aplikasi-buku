@extends('layouts.app')
@section('title', 'Edit Transaksi')
@section('content')
<h1>Edit Transaksi</h1>
<div class="card" style="max-width:600px">
    <p>{{ $loan->user->name }} meminjam <b>{{ $loan->book->judul }}</b></p>
    <form method="POST" action="{{ route('transaksi.update', $loan) }}">
        @csrf @method('PUT')
        <label>Tanggal Pinjam</label>
        <input type="date" name="tanggal_pinjam" value="{{ old('tanggal_pinjam', $loan->tanggal_pinjam->format('Y-m-d')) }}">
        <label>Tanggal Jatuh Tempo</label>
        <input type="date" name="tanggal_jatuh_tempo" value="{{ old('tanggal_jatuh_tempo', $loan->tanggal_jatuh_tempo->format('Y-m-d')) }}">
        <br>
        <button class="btn btn-green" type="submit">Simpan</button>
        <a class="btn btn-gray" href="{{ route('transaksi.index') }}">Batal</a>
    </form>
</div>
@endsection