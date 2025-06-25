@extends('layouts.main')

@section('container')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Detail Santri</div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>Nama</th>
                                <td>{{ $santri->nama }}</td>
                            </tr>
                            <tr>
                                <th>NIS</th>
                                <td>{{ $santri->nis }}</td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td>{{ $santri->alamat }}</td>
                            </tr>
                            <tr>
                                <th>No HP</th>
                                <td>{{ $santri->no_hp }}</td>
                            </tr>
                            <tr>
                                <th>Jenis Kelamin</th>
                                <td>{{ $santri->jenis_kelamin }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Masuk</th>
                                <td>{{ $santri->tanggal_masuk }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>{{ $santri->status }}</td>
                            </tr>
                        </table>

                        {{-- Rekap Pembayaran Santri --}}
                        <div class="mt-4">
                            <h5>Rekap Pembayaran</h5>
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Periode</th>
                                        <th>Total Bayar</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $pembayarans = \App\Models\Pembayaran::where('santri_id', $santri->id)
                                            ->orderBy('periode', 'desc')
                                            ->get()
                                            ->groupBy('periode');
                                        $biayas = \App\Models\Biaya::all();
                                        $biayaBebas = \App\Models\SantriBiayaBebas::where('santri_id', $santri->id)
                                            ->pluck('biaya_id')
                                            ->toArray();
                                        $totalWajib = $biayas
                                            ->where('unit', $santri->unit)
                                            ->whereNotIn('id', $biayaBebas)
                                            ->sum('jumlah');
                                    @endphp
                                    @forelse($pembayarans as $periode => $items)
                                        @php
                                            $totalBayar = $items->sum('jumlah_bayar');
                                            $status = $totalBayar >= $totalWajib ? 'Lunas' : 'Belum Lunas';
                                        @endphp
                                        <tr>
                                            <td>{{ $periode }}</td>
                                            <td>Rp {{ number_format($totalBayar, 0, ',', '.') }}</td>
                                            <td>
                                                @if ($status == 'Lunas')
                                                    <span class="badge bg-success">Lunas</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Belum Lunas</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">Belum ada pembayaran</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <a href="{{ route('santri.index') }}" class="btn btn-secondary">Kembali</a>
                        <a href="{{ route('santri.edit', $santri->id) }}" class="btn btn-primary">Edit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
