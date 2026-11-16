<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class Loan extends Model
{
    public const DENDA_PER_HARI = 1000;

    protected $fillable = [
        'user_id', 'book_id', 'tanggal_pinjam', 'tanggal_jatuh_tempo',
        'tanggal_kembali', 'status', 'denda',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pinjam' => 'date',
            'tanggal_jatuh_tempo' => 'date',
            'tanggal_kembali' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    // Fungsi: memproses peminjaman buku (stok berkurang 1)
    public static function pinjam(User $user, Book $book, int $hari = 7): self
    {
        return DB::transaction(function () use ($user, $book, $hari) {
            $buku = Book::whereKey($book->id)->lockForUpdate()->firstOrFail();

            if ($buku->stok < 1) {
                throw new RuntimeException('Stok buku "'.$buku->judul.'" sedang habis.');
            }

            $masihDipinjam = self::where('user_id', $user->id)
                ->where('book_id', $buku->id)
                ->where('status', 'dipinjam')
                ->exists();

            if ($masihDipinjam) {
                throw new RuntimeException('Buku ini masih dipinjam oleh anggota yang sama.');
            }

            $buku->decrement('stok');

            return self::create([
                'user_id' => $user->id,
                'book_id' => $buku->id,
                'tanggal_pinjam' => today(),
                'tanggal_jatuh_tempo' => today()->addDays($hari),
                'status' => 'dipinjam',
                'denda' => 0,
            ]);
        });
    }

    // Fungsi: memproses pengembalian buku (stok bertambah 1, hitung denda)
    public function kembalikan(): void
    {
        if ($this->status === 'dikembalikan') {
            return;
        }

        DB::transaction(function () {
            $hariIni = today();
            $telat = $this->tanggal_jatuh_tempo->lt($hariIni)
                ? (int) $this->tanggal_jatuh_tempo->diffInDays($hariIni)
                : 0;

            $this->update([
                'tanggal_kembali' => $hariIni,
                'status' => 'dikembalikan',
                'denda' => $telat * self::DENDA_PER_HARI,
            ]);

            $this->book()->increment('stok');
        });
    }
}