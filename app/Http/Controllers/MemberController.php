<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MemberController extends Controller
{
    private function rules(?User $member = null): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'username' => ['required', 'alpha_dash', 'max:30', Rule::unique('users', 'username')->ignore($member?->id)],
            'role' => ['required', Rule::in(['admin', 'siswa'])],
            'nis' => ['nullable', 'string', 'max:20', Rule::unique('users', 'nis')->ignore($member?->id)],
            'kelas' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'password' => [$member ? 'nullable' : 'required', 'string', 'min:6'],
        ];
    }

    public function index(Request $request): View
    {
        $kata = $request->q;

        $members = User::query()
            ->when($kata, fn ($q) => $q->where(function ($w) use ($kata) {
                $w->where('name', 'like', "%{$kata}%")
                  ->orWhere('username', 'like', "%{$kata}%")
                  ->orWhere('nis', 'like', "%{$kata}%")
                  ->orWhere('kelas', 'like', "%{$kata}%");
            }))
            ->orderBy('role')->orderBy('name')
            ->paginate(10)->withQueryString();

        return view('members.index', compact('members'));
    }

    public function create(): View
    {
        return view('members.form', ['member' => new User]);
    }

    public function store(Request $request): RedirectResponse
    {
        User::create($request->validate($this->rules()));

        return redirect()->route('anggota.index')->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function edit(User $member): View
    {
        return view('members.form', compact('member'));
    }

    public function update(Request $request, User $member): RedirectResponse
    {
        $data = $request->validate($this->rules($member));

        if (empty($data['password'])) {
            unset($data['password']); // password tidak diubah
        }

        $member->update($data);

        return redirect()->route('anggota.index')->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function destroy(Request $request, User $member): RedirectResponse
    {
        if ($member->id === $request->user()->id) {
            return back()->withErrors(['hapus' => 'Anda tidak dapat menghapus akun yang sedang dipakai.']);
        }

        if ($member->loans()->exists()) {
            return back()->withErrors(['hapus' => 'Anggota tidak dapat dihapus karena memiliki riwayat peminjaman.']);
        }

        $member->delete();

        return redirect()->route('anggota.index')->with('success', 'Anggota berhasil dihapus.');
    }
}