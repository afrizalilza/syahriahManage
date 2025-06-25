@extends('layouts.main')

@section('container')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Detail Pembayaran</div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>Santri</th>
                                <td>{{ $pembayaran->santri->nama ?? '-' }}
                                    @php
                                        $punyaBebas =
                                            \App\Models\SantriBiayaBebas::where(
                                                'santri_id',
                                                $pembayaran->santri_id,
                                            )->count() > 0;
                                    @endphp
                                    @if ($punyaBebas)
                                        <span class="ms-1 badge bg-info" title="Hak Bebas Biaya"><i
                                                class="fas fa-star"></i></span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Tanggal Bayar</th>
                                <td>{{ $pembayaran->tanggal_bayar }}</td>
                            </tr>
                            <tr>
                                <th>Periode</th>
                                <td>{{ $pembayaran->periode }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    @php
                                        // Ambil seluruh biaya wajib YANG TIDAK dibebaskan untuk santri ini
                                        $biayaBebas = \App\Models\SantriBiayaBebas::where(
                                            'santri_id',
                                            $pembayaran->santri_id,
                                        )
                                            ->pluck('biaya_id')
                                            ->toArray();
                                        $totalNominalWajib = $biayas
                                            ->where('unit', $pembayaran->santri->unit)
                                            ->whereNotIn('id', $biayaBebas)
                                            ->sum('jumlah');
                                        $totalBayarSantri = $grouped->sum('jumlah_bayar');
                                    @endphp
                                    @if ($totalNominalWajib == 0)
                                        <span class="badge bg-success">Lunas (Semua biaya dibebaskan)</span>
                                    @elseif ($totalBayarSantri >= $totalNominalWajib)
                                        <span class="badge bg-success">Lunas</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Belum Lunas</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                        <h5>Rincian Biaya</h5>
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Jenis Biaya</th>
                                    <th>Jumlah Bayar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($grouped as $item)
                                    <tr>
                                        <td>{{ $item->biaya->nama_biaya ?? '-' }}</td>
                                        <td>{{ number_format($item->jumlah_bayar, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <a href="{{ route('pembayaran.index') }}" class="btn btn-secondary">Kembali</a>
                        <a href="{{ route('pembayaran.edit', $pembayaran->id) }}" class="btn btn-primary">Edit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
