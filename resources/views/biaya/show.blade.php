@extends('layouts.main')

@section('container')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Detail Biaya</div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>Nama Biaya</th>
                                <td>{{ $biaya->nama_biaya }}</td>
                            </tr>
                            <tr>
                                <th>Jumlah</th>
                                <td>{{ number_format($biaya->jumlah, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Keterangan</th>
                                <td>{{ $biaya->keterangan }}</td>
                            </tr>
                        </table>
                        <a href="{{ route('biaya.index') }}" class="btn btn-secondary">Kembali</a>
                        <a href="{{ route('biaya.edit', $biaya->id) }}" class="btn btn-primary">Edit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
