<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $fillable = ['kode', 'judul', 'penulis', 'penerbit', 'tahun', 'kategori', 'stok'];

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    // Pencarian: kode, judul, penulis, atau kategori
    public function scopeCari(Builder $query, ?string $kata): Builder
    {
        if (! $kata) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($kata) {
            $q->where('judul', 'like', "%{$kata}%")
              ->orWhere('penulis', 'like', "%{$kata}%")
              ->orWhere('kode', 'like', "%{$kata}%")
              ->orWhere('kategori', 'like', "%{$kata}%");
        });
    }
}