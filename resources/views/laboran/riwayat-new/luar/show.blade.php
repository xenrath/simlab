@extends('layouts.app')

@section('title', 'Peminjaman Luar')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('laboran/riwayat-new') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Peminjaman Luar</h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Detail Peminjaman</h4>
                    <div class="card-header-action">
                        <span class="badge badge-success">Selesai</span>
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
                                    {{ date('d M Y', strtotime($pinjam->tanggal_awal)) }} -
                                    {{ date('d M Y', strtotime($pinjam->tanggal_akhir)) }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Laboran Penerima</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $pinjam->laboran_nama }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Klinik / Rumah Sakit</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $pinjam->keterangan }}
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
            @if (count($tagihan_peminjamans) > 0)
                <div class="card">
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
                                            <th class="align-middle" style="width: 80px">Jumlah</th>
                                            <th class="align-middle" style="width: 200px">Tanggal Pengembalian</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tagihan_peminjamans as $tagihan_peminjaman)
                                            <tr>
                                                <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                                <td class="align-middle">{{ $tagihan_peminjaman->nama }}</td>
                                                <td class="text-center align-middle">
                                                    {{ $tagihan_peminjaman->jumlah }}
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
