@extends('layouts.app')

@section('title', 'Riwayat Peminjaman')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('laboran/farmasi/riwayat') }}" class="btn btn-secondary rounded-0">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Riwayat Peminjaman</h1>
        </div>
        <div class="section-body">
            <div class="card mb-3 rounded-0">
                <div class="card-header">
                    <h4>Detail Peminjaman</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <strong>Praktik</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $pinjam->praktik->nama }}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <strong>Kategori</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $pinjam->kategori == 'normal' ? 'Mandiri' : 'Estafet' }}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <strong>Waktu</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ Carbon\Carbon::parse($pinjam->tanggal_awal)->translatedFormat('d F Y') }}
                                    -
                                    {{ Carbon\Carbon::parse($pinjam->tanggal_akhir)->translatedFormat('d F Y') }}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <strong>Ruang Lab</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $pinjam->ruang->nama }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <strong>Laboran</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $pinjam->laboran->nama ?? '-' }}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <strong>Mata Kuliah</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $pinjam->matakuliah }}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <strong>Dosen</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $pinjam->dosen }}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <strong>Peminjam</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $pinjam->peminjam->nama }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-3 rounded-0">
                <div class="card-header">
                    <h4>List Barang</h4>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-md mb-0">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 20px">No.</th>
                                <th>Nama Barang</th>
                                <th class="text-center" style="width: 100px">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($detail_pinjams as $detail_pinjam)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $detail_pinjam->barang->nama }}</strong><br>
                                        <small>({{ $detail_pinjam->barang->ruang->nama }})</small>
                                    </td>
                                    <td class="text-center">{{ $detail_pinjam->jumlah }} Pcs
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($pinjam->bahan)
                <div class="card rounded-0 mb-3">
                    <div class="card-header">
                        <h4>Detail Bahan</h4>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <strong>Bahan</strong>
                            </div>
                            <div class="col-md-10">
                                {{ $pinjam->bahan }}
                            </div>
                        </div>
                    </div>
                </div>
            @else
                @if (count($pinjam_detail_bahans))
                    <div class="card rounded-0 mb-3">
                        <div class="card-header">
                            <h4>List Bahan</h4>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-bordered table-striped table-md mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 20px">No.</th>
                                        <th>Nama Bahan</th>
                                        <th class="text-center" style="width: 100px">Jumlah</th>
                                        <th class="text-center" style="width: 100px">Satuan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pinjam_detail_bahans as $pinjam_detail_bahan)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>
                                                <strong>{{ $pinjam_detail_bahan->bahan_nama }}</strong><br>
                                                <small>({{ $pinjam_detail_bahan->prodi_nama }})</small>
                                            </td>
                                            <td class="text-center">
                                                {{ $pinjam_detail_bahan->jumlah }}
                                            </td>
                                            <td class="text-center">
                                                {{ ucfirst($pinjam_detail_bahan->satuan) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            @endif
            @if (count($tagihan_peminjamans) > 0)
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
                                <table class="table table-bordered table-striped table-md">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 20px">No</th>
                                            <th>Nama Barang</th>
                                            <th class="text-center" style="width: 100px">Jumlah</th>
                                            <th style="width: 160px">Tanggal Kembali</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tagihan_peminjamans as $tagihan_peminjaman)
                                            @php
                                                $rusak_hilang =
                                                    $tagihan_peminjaman->rusak + $tagihan_peminjaman->hilang;
                                            @endphp
                                            <tr>
                                                <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                                <td class="align-middle">
                                                    <strong>{{ $tagihan_peminjaman->detail_pinjam->barang->nama }}</strong>
                                                    <br>
                                                    <small>({{ $tagihan_peminjaman->detail_pinjam->barang->ruang->nama }})</small>
                                                </td>
                                                <td class="text-center align-middle">
                                                    {{ $tagihan_peminjaman->jumlah }} Pcs
                                                </td>
                                                <td class="align-middle">
                                                    {{ date('d M Y', strtotime($tagihan_peminjaman->created_at)) }}
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
