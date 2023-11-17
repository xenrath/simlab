@extends('layouts.app')

@section('title', 'Dalam Peminjaman')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('peminjam/k3/proses') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Dalam Peminjaman</h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Detail Peminjaman</h4>
                    <div class="card-header-action">
                        <span class="badge badge-primary">Proses</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Praktik</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $pinjam->praktik_nama }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Waktu</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $pinjam->jam_awal }} - {{ $pinjam->jam_akhir }},
                                    {{ date('d M Y', strtotime($pinjam->tanggal_awal)) }}
                                    @php
                                        $now = Carbon\Carbon::now()->format('Y-m-d');
                                        $expire = date('Y-m-d', strtotime($pinjam->tanggal_awal));
                                    @endphp
                                    @if ($now > $expire)
                                        <i class="fas fa-exclamation-circle text-danger"></i>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Ruang (Lab)</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $pinjam->ruang_nama }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Laboran</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $pinjam->laboran_nama }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Mata Kuliah</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $pinjam->matakuliah }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Praktik</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $pinjam->praktik }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Dosen</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $pinjam->dosen }}
                                </div>
                            </div>
                            <div class="row mb-3">
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
            <div class="card">
                <div class="card-header">
                    <h4>Detail Kelompok</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row mb-3">
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
            </div>
            <div class="card">
                <div class="card-header">
                    <h4>Detail Barang</h4>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered table-md">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 20px">No.</th>
                                <th>Nama Barang</th>
                                <th class="text-center" style="width: 40px">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($detail_pinjams as $detail_pinjam)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $detail_pinjam->barang_nama }}</td>
                                    <td class="text-center">{{ $detail_pinjam->jumlah }} Pcs</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4>Detail Bahan</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <strong>Bahan</strong>
                        </div>
                        <div class="col-md-10">
                            {{ $pinjam->bahan }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
