<?php

namespace Database\Seeders;

use App\Models\Pembayaran;
use Illuminate\Database\Seeder;

class PembayaranSeeder extends Seeder
{
    public function run(): void
    {
        Pembayaran::create([
            'santri_id' => 1,
            'biaya_id' => 1,
            'tanggal_bayar' => now()->subDays(5),
            'jumlah_bayar' => 150000,
        ]);
        Pembayaran::create([
            'santri_id' => 2,
            'biaya_id' => 2,
            'tanggal_bayar' => now()->subDays(2),
            'jumlah_bayar' => 50000,
        ]);
    }
}
