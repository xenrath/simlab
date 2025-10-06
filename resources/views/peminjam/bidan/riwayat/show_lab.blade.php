@extends('layouts.app')

@section('title', 'Riwayat Peminjaman')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('peminjam/bidan/riwayat') }}" class="btn btn-secondary rounded-0">
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
                                    {{ Carbon\Carbon::parse($pinjam->tanggal_awal)->translatedFormat('d M Y') }}
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
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <strong>Laboran</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $pinjam->laboran->nama ?? $pinjam->ruang->laboran->nama }}
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
            </div>
            @if (count($data_kelompok))
                <div class="card rounded-0 mb-3">
                    <div class="card-header">
                        <h4>Detail Kelompok</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row mb-2">
                                    <div class="col-md-4">
                                        <strong>Ketua</strong>
                                    </div>
                                    <div class="col-md-8">
                                        {{ $data_kelompok['ketua']['kode'] }} | {{ $data_kelompok['ketua']['nama'] }}
                                    </div>
                                </div>
                                <div class="row mb-2">
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
                </div>
            @endif
            <div class="card rounded-0 mb-3">
                <div class="card-header">
                    <h4>List Barang</h4>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered table-striped table-md mb-0">
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
                                    <td class="text-center">{{ $detail_pinjam->jumlah }} Pcs</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
