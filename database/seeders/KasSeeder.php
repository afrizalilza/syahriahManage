<?php

namespace Database\Seeders;

use App\Models\Kas;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class KasSeeder extends Seeder
{
    public function run()
    {
        // Kas 1
        $kas1 = Kas::create(['nama' => 'Kos Makan']);
        $kas2 = Kas::create(['nama' => 'Madrasah Diniyah']);

        // Pemasukan Kos Makan
        Pemasukan::create([
            'kas_id' => $kas1->id,
            'tanggal' => Carbon::parse('2025-04-01'),
            'nama' => "Yusuf Asy'ari",
            'nominal' => 100000,
            'keterangan' => 'Pembayaran Syahriah',
        ]);
        Pemasukan::create([
            'kas_id' => $kas1->id,
            'tanggal' => Carbon::parse('2025-04-02'),
            'nama' => 'Afizal Munadhif',
            'nominal' => 100000,
            'keterangan' => 'Pembayaran Syahriah',
        ]);

        // Pengeluaran Kos Makan
        Pengeluaran::create([
            'kas_id' => $kas1->id,
            'tanggal' => Carbon::parse('2025-04-05'),
            'nama' => 'Beli Beras',
            'nominal' => 500000,
            'keterangan' => '-',
            'bukti' => null,
        ]);
        Pengeluaran::create([
            'kas_id' => $kas1->id,
            'tanggal' => Carbon::parse('2025-04-10'),
            'nama' => 'Perbaikan Listrik',
            'nominal' => 700000,
            'keterangan' => 'Darurat',
            'bukti' => null,
        ]);

        // Data dummy kas 2
        Pemasukan::create([
            'kas_id' => $kas2->id,
            'tanggal' => Carbon::parse('2025-04-03'),
            'nama' => 'Ahmad Fauzi',
            'nominal' => 120000,
            'keterangan' => 'Pembayaran Madin',
        ]);
        Pengeluaran::create([
            'kas_id' => $kas2->id,
            'tanggal' => Carbon::parse('2025-04-07'),
            'nama' => 'Beli Buku',
            'nominal' => 30000,
            'keterangan' => '-',
            'bukti' => null,
        ]);
    }
}
