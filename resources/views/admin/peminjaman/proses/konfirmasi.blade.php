@extends('layouts.app')

@section('title', 'Peminjaman Proses Konfirmasi')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('admin/peminjaman') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Peminjaman Proses</h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Detail Peminjaman</h4>
                    <div class="card-header-action">
                        <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i
                                class="fas fa-minus"></i></a>
                    </div>
                </div>
                <div class="collapse" id="mycard-collapse">
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
                                        {{ $peminjaman_tamu->lama }}
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
            </div>
            @if (session('errors'))
                <div class="alert alert-danger alert-has-icon alert-dismissible show fade">
                    <div class="alert-body">
                        <div class="alert-title">GAGAL !</div>
                        <button class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                        <ul class="px-3 mb-0">
                            @foreach (session('errors') as $error)
                                <li>{!! $error !!}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <h4>Konfirmasi Pengembalian</h4>
					<small style="line-height: 1.5">(kosongkan saja jika tidak ada barang rusak / hilang)</small>
                </div>
                <form action="{{ url('admin/peminjaman/proses/konfirmasi_selesai/' . $peminjaman_tamu->id) }}" method="POST">
                    @csrf
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-md">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 20px">No</th>
                                        <th>Nama Barang</th>
                                        <th style="width: 40px">Jumlah</th>
                                        <th style="width: 180px">Rusak</th>
                                        <th style="width: 180px">Hilang</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($detail_peminjaman_tamus as $detail_peminjaman_tamu)
                                        @php
                                            $rusak = 0;
                                            $hilang = 0;
                                            if (session('datas')) {
                                                $rusak = session('datas')[$detail_peminjaman_tamu->id]['rusak'];
                                                $hilang = session('datas')[$detail_peminjaman_tamu->id]['hilang'];
                                            }
                                        @endphp
                                        <tr>
                                            <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                            <td class="align-middle">{{ $detail_peminjaman_tamu->nama }}</td>
                                            <td class="align-middle text-center">{{ $detail_peminjaman_tamu->total }}</td>
                                            @php
                                                $total = $detail_peminjaman_tamu->total;
                                            @endphp
                                            <td>
                                                <input type="number" name="rusak-{{ $detail_peminjaman_tamu->id }}"
                                                    class="form-control"
                                                    oninput="this.value = !!this.value && Math.abs(this.value) >= 0 && !!this.value && Math.abs(this.value) <= {{ $total }} ? Math.abs(this.value) : 0"
                                                    value="{{ $rusak }}">
                                            </td>
                                            <td>
                                                <input type="number" name="hilang-{{ $detail_peminjaman_tamu->id }}"
                                                    class="form-control"
                                                    oninput="this.value = !!this.value && Math.abs(this.value) >= 0 && !!this.value && Math.abs(this.value) <= {{ $total }} ? Math.abs(this.value) : 0"
                                                    value="{{ $hilang }}">
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center" colspan="6">- Tidak ada barang yang dipinjam -</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary">
                            Konfirmasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
