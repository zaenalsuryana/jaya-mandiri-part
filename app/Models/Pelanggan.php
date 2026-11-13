<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_pelangggan',
        'user_id',
        'tipe',
        'nama',
        'telepon',
        'email',
        'npwp',
        'status',
        'tingkat_harga',
        'lama_tempo',
        'batas_kredit',
        'is_default',
        'catatan',

    ];

    protected static function booted()
    {
        static::creating(function ($pelanggan) {
            if (empty($pelanggan->kode_pelanggan)) {
                $count = self::count() + 1;
                $pelanggan->kode_pelanggan = 'CUS-' . str_pad($count, 5, '0', STR_PAD_LEFT);
            }
        });
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
