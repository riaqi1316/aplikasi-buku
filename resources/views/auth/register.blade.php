@extends('layouts.guest')
@section('title', 'Daftar Anggota')
@section('content')
<form method="POST" action="{{ route('daftar') }}">
    @csrf
    <label>Nama Lengkap</label>
    <input name="name" value="{{ old('name') }}">
    <label>Username</label>
    <input name="username" value="{{ old('username') }}">
    <label>NIS</label>
    <input name="nis" value="{{ old('nis') }}">
    <label>Kelas</label>
    <input name="kelas" value="{{ old('kelas') }}" placeholder="contoh: XII RPL 1">
    <label>Alamat</label>
    <input name="alamat" value="{{ old('alamat') }}">
    <label>Password</label>
    <input type="password" name="password">
    <label>Konfirmasi Password</label>
    <input type="password" name="password_confirmation">
    <br>
    <button class="btn" style="width:100%" type="submit">Daftar</button>
</form>
<p class="muted" style="text-align:center">Sudah punya akun? <a href="{{ route('login') }}">Login</a></p>
@endsection