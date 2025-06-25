<?php

namespace App\Models;

use App\Models\Scopes\UnitScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Biaya extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_biaya', 'jumlah', 'keterangan', 'unit',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new UnitScope);
    }

    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class);
    }
}
