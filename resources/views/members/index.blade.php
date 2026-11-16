@extends('layouts.app')
@section('title', 'Kelola Anggota')
@section('content')
<h1>Kelola Anggota</h1>
<div class="card">
    <form class="toolbar" method="GET" action="{{ route('anggota.index') }}">
        <input name="q" value="{{ request('q') }}" placeholder="Cari nama, username, NIS, atau kelas...">
        <button class="btn" type="submit">Cari</button>
        <a class="btn btn-green" href="{{ route('anggota.create') }}">+ Tambah Anggota</a>
    </form>
    <table>
        <tr><th>Nama</th><th>Username</th><th>Role</th><th>NIS</th><th>Kelas</th><th>Aksi</th></tr>
        @forelse ($members as $m)
            <tr>
                <td>{{ $m->name }}</td>
                <td>{{ $m->username }}</td>
                <td><span class="badge {{ $m->role === 'admin' ? 'b-red' : 'b-blue' }}">{{ $m->role }}</span></td>
                <td>{{ $m->nis ?? '-' }}</td>
                <td>{{ $m->kelas ?? '-' }}</td>
                <td>
                    <a class="btn btn-sm" href="{{ route('anggota.edit', $m) }}">Edit</a>
                    <form class="inline" method="POST" action="{{ route('anggota.destroy', $m) }}"
                          onsubmit="return confirm('Hapus anggota ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-red" type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="muted">Anggota tidak ditemukan.</td></tr>
        @endforelse
    </table>
    {{ $members->links('pagination::bootstrap-4') }}
</div>
@endsection