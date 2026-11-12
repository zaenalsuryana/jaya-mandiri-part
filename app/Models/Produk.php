<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_produk',
        'slug',
        'kategori_id',
        'merek_id',
        'nomor_part',
        'deskripsi',
        'status',
    ];

    public function kategori()
    {
        return $this->belongsTo(Merek::class);
    }
}
