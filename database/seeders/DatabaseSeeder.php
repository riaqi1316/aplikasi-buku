<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrator Perpustakaan',
            'username' => 'admin',
            'password' => 'admin123',
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Siswa Contoh',
            'username' => 'siswa',
            'password' => 'siswa123',
            'role' => 'siswa',
            'nis' => '2526001',
            'kelas' => 'XII RPL 1',
            'alamat' => 'Jl. Merdeka No. 1',
        ]);

        $buku = [
            ['B001', 'Laskar Pelangi', 'Andrea Hirata', 'Bentang Pustaka', 2005, 'Novel', 5],
            ['B002', 'Bumi Manusia', 'Pramoedya Ananta Toer', 'Hasta Mitra', 1980, 'Novel', 3],
            ['B003', 'Pemrograman Web dengan Laravel', 'Tim Penulis RPL', 'Informatika', 2024, 'Teknologi', 4],
            ['B004', 'Basis Data MySQL untuk Pemula', 'Riaqi', 'Informatika', 2022, 'Teknologi', 4],
            ['B005', 'Matematika SMK Kelas XII', 'Tim Kemdikbud', 'Kemdikbud', 2023, 'Pelajaran', 6],
            ['B006', 'Bahasa Indonesia SMK', 'Tim Kemdikbud', 'Kemdikbud', 2023, 'Pelajaran', 6],
            ['B007', 'Atomic Habits (Terjemahan)', 'James Clear', 'Gramedia', 2019, 'Pengembangan Diri', 2],
            ['B008', 'Sang Pemimpi', 'Andrea Hirata', 'Bentang Pustaka', 2006, 'Novel', 3],
        ];

        foreach ($buku as [$kode, $judul, $penulis, $penerbit, $tahun, $kategori, $stok]) {
            Book::create(compact('kode', 'judul', 'penulis', 'penerbit', 'tahun', 'kategori', 'stok'));
        }
    }
}