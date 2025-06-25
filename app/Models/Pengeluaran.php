<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'biaya_id', 'kas_id', 'tanggal', 'nama', 'nominal', 'keterangan', 'bukti', 'unit',
    ];

    public function biaya()
    {
        return $this->belongsTo(Biaya::class);
    }

    public function kas()
    {
        return $this->belongsTo(Kas::class);
    }
}
