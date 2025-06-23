@extends('layouts.app')

@section('title', 'Riwayat Peminjaman')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('admin/riwayat') }}" class="btn btn-secondary rounded-0">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Riwayat Peminjaman</h1>
        </div>
        <div class="section-body">
            <div class="card rounded-0 mb-3">
                <div class="card-header">
                    <h4>Detail Peminjaman</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <strong>Nama Tamu</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $peminjaman_tamu->tamu->nama ?? '-' }}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <strong>Asal Institusi</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $peminjaman_tamu->tamu->institusi ?? '-' }}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <strong>No. Telepon</strong>
                                </div>
                                <div class="col-md-8">
                                    @if ($peminjaman_tamu->tamu)
                                        <a href="{{ url('admin/hubungi_tamu/' . $peminjaman_tamu->tamu_id) }}"
                                            target="_blank">
                                            +62{{ $peminjaman_tamu->tamu->telp }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <strong>Alamat</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $peminjaman_tamu->tamu->alamat ?? '-' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <strong>Waktu Peminjaman</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ date('d M Y', strtotime($peminjaman_tamu->tanggal_awal)) }} -
                                    {{ date('d M Y', strtotime($peminjaman_tamu->tanggal_akhir)) }}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <strong>Lama</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $peminjaman_tamu->lama }} Hari
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <strong>Keperluan</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $peminjaman_tamu->keperluan ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card rounded-0 mb-3">
                <div class="card-header">
                    <h4>Detail Barang</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-md mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 20px">No</th>
                                    <th>Nama Barang</th>
                                    <th>Ruang</th>
                                    <th class="text-center" style="width: 80px">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($detail_peminjaman_tamus as $detail_peminjaman_tamu)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $detail_peminjaman_tamu->barang->nama }}</td>
                                        <td>{{ $detail_peminjaman_tamu->barang->ruang->nama }}</td>
                                        <td class="text-center">{{ $detail_peminjaman_tamu->total }} Pcs</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @if (count($tagihan_peminjaman_tamus) > 0)
                <div class="card rounded-0 mb-3">
                    <div class="card-header">
                        <h4>Riwayat Tagihan</h4>
                        <div class="card-header-action">
                            <a data-collapse="#card-tagihan" class="btn btn-icon btn-info" href="#"><i
                                    class="fas fa-plus"></i></a>
                        </div>
                    </div>
                    <div class="collapse" id="card-tagihan">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-md">
                                    <thead>
                                        <tr>
                                            <th class="text-center align-middle" style="width: 20px">No</th>
                                            <th class="align-middle">Nama Barang</th>
                                            <th class="align-middle" style="width: 100px">Jumlah</th>
                                            <th class="align-middle" style="width: 200px">Tanggal Pengembalian</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tagihan_peminjaman_tamus as $tagihan_peminjaman_tamu)
                                            @php
                                                $rusak_hilang =
                                                    $tagihan_peminjaman_tamu->rusak + $tagihan_peminjaman_tamu->hilang;
                                            @endphp
                                            <tr>
                                                <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                                <td class="align-middle">
                                                    {{ $tagihan_peminjaman_tamu->detail_peminjaman_tamu->barang->nama }}
                                                </td>
                                                <td class="text-center align-middle">
                                                    {{ $tagihan_peminjaman_tamu->jumlah }} Pcs
                                                </td>
                                                <td class="align-middle">
                                                    {{ date('d M Y', strtotime($tagihan_peminjaman_tamu->created_at)) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
