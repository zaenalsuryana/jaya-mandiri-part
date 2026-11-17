<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_purchase',
        'pemasok_id',
        'tanggal_beli',
        'status',
        'total_biaya',
        'catatan',
    ];

    protected static function booted()
    {
        static::creating(function ($purchase) {
            if (empty($purchase->kode_purchase)) {
                $datePrefix = date('ymd');
                $count = self::whereDate('created_at', today())->count() + 1;
                $purchase->kode_purchase = 'PO-' . $datePrefix . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function pemasok()
    {
        return $this->belongsTo(Pemasok::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }
}
