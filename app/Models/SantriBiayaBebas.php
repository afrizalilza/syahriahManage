<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SantriBiayaBebas extends Model
{
    use HasFactory;

    protected $table = 'santri_biaya_bebas';

    protected $fillable = [
        'santri_id', 'biaya_id', 'keterangan',
    ];

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    public function biaya()
    {
        return $this->belongsTo(Biaya::class);
    }
}
