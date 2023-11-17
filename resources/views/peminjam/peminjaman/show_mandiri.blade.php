@extends('layouts.app')

@section('title', 'Detail Peminjaman')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('peminjam/normal/peminjaman') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Detail Peminjaman</h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Peminjaman</h4>
                    <div class="card-header-action">
                        @php
                            $now = Carbon\Carbon::now()->format('Y-m-d');
                            $expire = date('Y-m-d', strtotime($pinjam->tanggal_awal));
                        @endphp
                        @if ($now > $expire)
                            <span class="badge badge-danger">Kadaluarsa</span>
                        @else
                            <span class="badge badge-warning">Menunggu</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Praktik</strong>
                                </div>
                                @php
                                    if ($pinjam->kategori == 'normal') {
                                        $kategori = 'Mandiri';
                                    } else {
                                        $kategori = 'Estafet';
                                    }
                                @endphp
                                <div class="col-md-8">
                                    {{ $pinjam->praktik_nama }} ({{ $kategori }})
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Waktu</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ date('d M Y', strtotime($pinjam->tanggal_awal)) }} -
                                    {{ date('d M Y', strtotime($pinjam->tanggal_akhir)) }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Ruang Lab.</strong>
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
                                    <strong>Dosen</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $pinjam->dosen }}
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
                    <div class="table-responsive">
                        <table class="table table-bordered table-md">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 20px">No.</th>
                                    <th>Nama Barang</th>
                                    <th style="width: 160px">Ruang</th>
                                    <th class="text-center" style="width: 100px">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($detailpinjams as $detailpinjam)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $detailpinjam->barang_nama }}</td>
                                        <td>Lab. Farmakologi</td>
                                        <td class="text-center">{{ $detailpinjam->jumlah }} Pcs</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @if ($pinjam->bahan)
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
            @endif
        </div>
    </section>
@endsection
