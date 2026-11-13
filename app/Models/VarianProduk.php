<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VarianProduk extends Model
{
    use HasFactory;

    protected $fillable = [
        'produk_id',
        'nama_varian',
        'sku',
        'stok',
        'harga_beli',
        'harga_jual',
        'harga_grosir',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}