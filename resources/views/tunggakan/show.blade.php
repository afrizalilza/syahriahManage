@extends('layouts.main')

@section('container')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Detail Tunggakan - {{ $periode }} (Jatuh Tempo: {{ $jatuhTempo }})</div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>Santri</th>
                                <td>{{ $santri->nama ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Periode</th>
                                <td>{{ $periode }}</td>
                            </tr>
                            <tr>
                                <th>Total Tunggakan</th>
                                <td>Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Jatuh Tempo</th>
                                <td>{{ $jatuhTempo }}</td>
                            </tr>
                        </table>
                        <h5>Rincian Tunggakan Per Biaya</h5>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nama Biaya</th>
                                    <th>Jumlah Tunggakan</th>
                                    <th>Jatuh Tempo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rincian as $item)
                                    <tr>
                                        <td>{{ $item['biaya']->nama_biaya }}</td>
                                        <td>Rp {{ number_format($item['sisa'], 0, ',', '.') }}</td>
                                        <td>{{ $item['jatuh_tempo'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Tidak ada rincian tunggakan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <a href="{{ route('tunggakan.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
