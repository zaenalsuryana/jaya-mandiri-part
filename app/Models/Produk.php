<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    protected static function booted()
    {
        static::creating(function ($produk) {
            if (empty($produk->slug) && !empty($produk->nama_produk)) {
                $produk->slug = Str::slug(produk->nama_produk) . '-' . Str::loer(Str::random(4));
            }
        });
    }

    public function kategori()
    {
        return $this->belongsTo(Merek::class);
    }

    public function merek()
    {
        return $this->belongsTo(Merek::class);
    }

    public function varianProduks()
    {
        return $this->hasMany(VarianProduk::class);
    }
}
