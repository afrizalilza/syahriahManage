<?php

namespace Database\Seeders;

use App\Models\Santri;
use Illuminate\Database\Seeder;

class SantriSeeder extends Seeder
{
    public function run(): void
    {
        Santri::create([
            'nama' => 'Ahmad Fauzi',
            'nis' => '2025001',
            'alamat' => 'Jl. Pesantren No. 1',
            'no_hp' => '08123456789',
        ]);
        Santri::create([
            'nama' => 'Siti Aminah',
            'nis' => '2025002',
            'alamat' => 'Jl. Pesantren No. 2',
            'no_hp' => '08129876543',
        ]);
    }
}
