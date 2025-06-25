<?php

namespace App\Models;

use App\Models\Scopes\UnitScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    use HasFactory;

    protected $fillable = [
        'santri_id', 'biaya_id', 'periode', 'tanggal_bayar', 'jumlah_bayar', 'status', 'kode_transaksi',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new UnitScope);
    }

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    public function biaya()
    {
        return $this->belongsTo(Biaya::class);
    }
}
