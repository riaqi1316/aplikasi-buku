@extends('layouts.guest')
@section('title', 'Login')
@section('content')
<form method="POST" action="{{ route('login') }}">
    @csrf
    <label for="username">Username</label>
    <input id="username" name="username" value="{{ old('username') }}" autofocus>

    <label for="password">Password</label>
    <input id="password" type="password" name="password">

    <label style="font-weight:400"><input type="checkbox" name="remember" style="width:auto"> Ingat saya</label>
    <br>
    <button class="btn" style="width:100%" type="submit">Login</button>
</form>
<p class="muted" style="text-align:center">Belum jadi anggota? <a href="{{ route('daftar') }}">Daftar di sini</a></p>
@endsection