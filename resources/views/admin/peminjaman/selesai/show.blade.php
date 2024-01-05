@extends('layouts.app')

@section('title', 'Peminjaman Selesai')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('admin/peminjaman/selesai') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Peminjaman Selesai</h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Detail Peminjaman</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Nama Tamu</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $peminjaman_tamu->tamu_nama }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Asal Instansi</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $peminjaman_tamu->tamu_alamat }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>No. Telepon</strong>
                                </div>
                                <div class="col-md-8">
                                    +62{{ $peminjaman_tamu->tamu_telp }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Waktu Peminjaman</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ date('d M Y', strtotime($peminjaman_tamu->tanggal_awal)) }} -
                                    {{ date('d M Y', strtotime($peminjaman_tamu->tanggal_akhir)) }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Lama</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $peminjaman_tamu->lama }} Hari
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Keperluan</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $peminjaman_tamu->keperluan }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header">
                    <h4>Detail Barang</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-md">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 20px">No</th>
                                    <th>Nama Barang</th>
                                    <th class="text-center" style="width: 80px">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($detail_peminjaman_tamus as $detail_peminjaman_tamu)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $detail_peminjaman_tamu->nama }}</td>
                                        <td class="text-center">{{ $detail_peminjaman_tamu->total }} Pcs</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
