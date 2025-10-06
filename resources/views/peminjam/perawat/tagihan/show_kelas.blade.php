@extends('layouts.app')

@section('title', 'Riwayat Peminjaman')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('peminjam/perawat/tagihan') }}" class="btn btn-secondary rounded-0">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Riwayat Peminjaman</h1>
        </div>
        <div class="section-body">
            <div class="card rounded-0 mb-3">
                <div class="card-header">
                    <h4>Detail Peminjaman</h4>
                    <div class="card-header-action">
                        <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#">
                            <i class="fas fa-plus"></i>
                        </a>
                    </div>
                </div>
                <div class="collapse" id="mycard-collapse">
                    <div class="card-body">
                        <div class="row mb-2">
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
                                        <strong>Waktu</strong>
                                    </div>
                                    <div class="col-md-8">
                                        {{ $pinjam->jam_awal }}-{{ $pinjam->jam_akhir }} WIB,
                                        {{ date('d M Y', strtotime($pinjam->tanggal_awal)) }}
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-4">
                                        <strong>Ruang Kelas</strong>
                                    </div>
                                    <div class="col-md-8">
                                        {{ $pinjam->keterangan }}
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-4">
                                        <strong>Laboran Penerima</strong>
                                    </div>
                                    <div class="col-md-8">
                                        {{ $pinjam->laboran->nama }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
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
                                        <strong>Praktik</strong>
                                    </div>
                                    <div class="col-md-8">
                                        {{ $pinjam->praktik_keterangan }}
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
                                        <strong>Kelas</strong>
                                    </div>
                                    <div class="col-md-8">
                                        {{ $pinjam->kelas }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($data_kelompok)
                        <div class="card-body pb-2 border-top">
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <div class="row mb-2">
                                        <div class="col-md-4">
                                            <strong>Ketua</strong>
                                        </div>
                                        <div class="col-md-8">
                                            {{ $data_kelompok['ketua']['kode'] }} | {{ $data_kelompok['ketua']['nama'] }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong>Anggota</strong>
                                        </div>
                                        <div class="col-md-8">
                                            @php
                                                $anggotas = $data_kelompok['anggota'];
                                            @endphp
                                            <ul class="p-0" style="list-style: none">
                                                @foreach ($anggotas as $anggota)
                                                    <li>{{ $anggota['kode'] }} | {{ $anggota['nama'] }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($pinjam->bahan)
                        <div class="card-body border-top">
                            <div class="row">
                                <div class="col-md-2">
                                    <strong>Bahan</strong>
                                </div>
                                <div class="col-md-10">
                                    {{ $pinjam->bahan }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="card rounded-0 mb-3">
                <div class="card-header">
                    <h4>Detail Barang</h4>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered table-striped table-md">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 20px">No.</th>
                                <th>Nama Barang</th>
                                <th class="text-center" style="width: 100px">Jumlah</th>
                                <th class="text-center" style="width: 100px">Rusak</th>
                                <th class="text-center" style="width: 100px">Hilang</th>
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
                                    <td class="text-center">{{ $detail_pinjam->jumlah }} Pcs</td>
                                    <td class="text-center">
                                        @if ($detail_pinjam->rusak > 0)
                                            {{ $detail_pinjam->rusak }} Pcs
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($detail_pinjam->hilang > 0)
                                            {{ $detail_pinjam->hilang }} Pcs
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
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
