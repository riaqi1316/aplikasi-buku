@extends('layouts.app')
@section('title', $book->exists ? 'Edit Buku' : 'Tambah Buku')
@section('content')
<h1>{{ $book->exists ? 'Edit Buku' : 'Tambah Buku' }}</h1>
<div class="card" style="max-width:600px">
    <form method="POST" action="{{ $book->exists ? route('buku.update', $book) : route('buku.store') }}">
        @csrf
        @if ($book->exists) @method('PUT') @endif

        <label>Kode Buku</label>
        <input name="kode" value="{{ old('kode', $book->kode) }}">
        <label>Judul</label>
        <input name="judul" value="{{ old('judul', $book->judul) }}">
        <label>Penulis</label>
        <input name="penulis" value="{{ old('penulis', $book->penulis) }}">
        <label>Penerbit</label>
        <input name="penerbit" value="{{ old('penerbit', $book->penerbit) }}">
        <label>Tahun Terbit</label>
        <input type="number" name="tahun" value="{{ old('tahun', $book->tahun) }}">
        <label>Kategori</label>
        <input name="kategori" value="{{ old('kategori', $book->kategori) }}">
        <label>Stok</label>
        <input type="number" name="stok" min="0" value="{{ old('stok', $book->stok ?? 0) }}">
        <br>
        <button class="btn btn-green" type="submit">Simpan</button>
        <a class="btn btn-gray" href="{{ route('buku.index') }}">Batal</a>
    </form>
</div>
@endsection