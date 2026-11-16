@extends('layouts.app')
@section('title', 'Transaksi Baru')
@section('content')
<h1>Transaksi Peminjaman Baru</h1>
<div class="card" style="max-width:600px">
    <form method="POST" action="{{ route('transaksi.store') }}">
        @csrf
        <label>Anggota</label>
        <select name="user_id">
            <option value="">-- pilih anggota --</option>
            @foreach ($users as $u)
                <option value="{{ $u->id }}" @selected(old('user_id') == $u->id)>{{ $u->name }} ({{ $u->nis }})</option>
            @endforeach
        </select>
        <label>Buku (hanya yang stoknya tersedia)</label>
        <select name="book_id">
            <option value="">-- pilih buku --</option>
            @foreach ($books as $b)
                <option value="{{ $b->id }}" @selected(old('book_id') == $b->id)>{{ $b->judul }} (stok {{ $b->stok }})</option>
            @endforeach
        </select>
        <label>Lama Pinjam (hari)</label>
        <input type="number" name="lama" min="1" max="30" value="{{ old('lama', 7) }}">
        <br>
        <button class="btn btn-green" type="submit">Simpan</button>
        <a class="btn btn-gray" href="{{ route('transaksi.index') }}">Batal</a>
    </form>
</div>
@endsection