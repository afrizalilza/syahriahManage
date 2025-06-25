<?php

namespace Database\Seeders;

use App\Models\Tunggakan;
use Illuminate\Database\Seeder;

class TunggakanSeeder extends Seeder
{
    public function run(): void
    {
        Tunggakan::create([
            'santri_id' => 1,
            'biaya_id' => 2,
            'jumlah_tunggakan' => 50000,
            'tanggal_jatuh_tempo' => now()->addDays(10),
        ]);
        Tunggakan::create([
            'santri_id' => 2,
            'biaya_id' => 1,
            'jumlah_tunggakan' => 150000,
            'tanggal_jatuh_tempo' => now()->addDays(15),
        ]);
    }
}
