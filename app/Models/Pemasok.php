<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemasok extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_pemasok',
        'nama',
        'telepon',
        'email',
        'alamat',
        'lama_tempo',
        'catatan',
        'is_active',
    ];

    // Otomatis generate kode pemasok: SUP-00001, dst.
    protected static function booted()
    {
        static::creating(function ($pemasok) {
            if (empty($pemasok->kode_pemasok)) {
                $count = self::count() + 1;
                $pemasok->kode_pemasok = 'SUP-' . str_pad($count, 5, '0', STR_PAD_LEFT);
            }
        });
    }
}