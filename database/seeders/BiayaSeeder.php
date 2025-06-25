<?php

namespace Database\Seeders;

use App\Models\Biaya;
use Illuminate\Database\Seeder;

class BiayaSeeder extends Seeder
{
    public function run(): void
    {
        Biaya::create([
            'nama_biaya' => 'SPP Bulanan',
            'jumlah' => 150000,
            'keterangan' => 'SPP wajib setiap bulan',
        ]);
        Biaya::create([
            'nama_biaya' => 'Uang Makan',
            'jumlah' => 50000,
            'keterangan' => 'Uang makan bulanan',
        ]);
    }
}
