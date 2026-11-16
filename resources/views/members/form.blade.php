@extends('layouts.app')
@section('title', $member->exists ? 'Edit Anggota' : 'Tambah Anggota')
@section('content')
<h1>{{ $member->exists ? 'Edit Anggota' : 'Tambah Anggota' }}</h1>
<div class="card" style="max-width:600px">
    <form method="POST" action="{{ $member->exists ? route('anggota.update', $member) : route('anggota.store') }}">
        @csrf
        @if ($member->exists) @method('PUT') @endif

        <label>Nama Lengkap</label>
        <input name="name" value="{{ old('name', $member->name) }}">
        <label>Username</label>
        <input name="username" value="{{ old('username', $member->username) }}">
        <label>Role</label>
        <select name="role">
            @foreach (['siswa', 'admin'] as $r)
                <option value="{{ $r }}" @selected(old('role', $member->role ?? 'siswa') === $r)>{{ ucfirst($r) }}</option>
            @endforeach
        </select>
        <label>NIS</label>
        <input name="nis" value="{{ old('nis', $member->nis) }}">
        <label>Kelas</label>
        <input name="kelas" value="{{ old('kelas', $member->kelas) }}">
        <label>Alamat</label>
        <input name="alamat" value="{{ old('alamat', $member->alamat) }}">
        <label>Password @if ($member->exists)<span class="muted">(kosongkan jika tidak diubah)</span>@endif</label>
        <input type="password" name="password">
        <br>
        <button class="btn btn-green" type="submit">Simpan</button>
        <a class="btn btn-gray" href="{{ route('anggota.index') }}">Batal</a>
    </form>
</div>
@endsection