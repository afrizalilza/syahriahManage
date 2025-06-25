<?php

namespace App\Models;

use App\Models\Scopes\UnitScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Santri extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama', 'nis', 'alamat', 'no_hp', 'jenis_kelamin', 'tanggal_masuk', 'status', 'unit',
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
