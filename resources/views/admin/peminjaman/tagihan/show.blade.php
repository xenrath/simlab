@extends('layouts.app')

@section('title', 'Tagihan')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('admin/peminjaman/tagihan') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Tagihan</h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Detail Peminjaman</h4>
                    <div class="card-header-action">
                        <a data-collapse="#card-detail" class="btn btn-icon btn-info" href="#"><i
                                class="fas fa-plus"></i></a>
                    </div>
                </div>
                <div class="collapse" id="card-detail">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <strong>Nama Tamu</strong>
                                    </div>
                                    <div class="col-md-8">
                                        {{ $peminjaman_tamu->tamu->nama }}
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <strong>Asal Instansi</strong>
                                    </div>
                                    <div class="col-md-8">
                                        {{ $peminjaman_tamu->tamu->alamat }}
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <strong>No. Telepon</strong>
                                    </div>
                                    <div class="col-md-8">
                                        {{ $peminjaman_tamu->tamu->telp }}
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
            <div class="card">
                <div class="card-header">
                    <h4>Konfirmasi Pengembalian</h4>
                </div>
                <form action="{{ url('admin/tagihan/' . $peminjaman_tamu->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-md">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle" style="width: 20px">No</th>
                                        <th class="align-middle">Nama Barang</th>
                                        <th class="text-center align-middle" style="width: 140px">Rusak / Hilang</th>
                                        <th class="align-middle" style="width: 240px">Dikembalikan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($detail_peminjaman_tamus as $detail_peminjaman_tamu)
                                        <tr>
                                            <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                            <td class="align-middle">{{ $detail_peminjaman_tamu->nama }}</td>
                                            @php
                                                if (array_key_exists($detail_peminjaman_tamu->id, $tagihan_detail)) {
                                                    $rusak_hilang = $detail_peminjaman_tamu->rusak + $detail_peminjaman_tamu->hilang - $tagihan_detail[$detail_peminjaman_tamu->id];
                                                } else {
                                                    $rusak_hilang = $detail_peminjaman_tamu->rusak + $detail_peminjaman_tamu->hilang;
                                                }
                                            @endphp
                                            <td class="text-center align-middle">{{ $rusak_hilang }}</td>
                                            <td>
                                                <input type="number" class="form-control"
                                                    name="jumlah[{{ $detail_peminjaman_tamu->id }}]"
                                                    oninput="this.value = !!this.value && Math.abs(this.value) >= 0 && !!this.value && Math.abs(this.value) <= {{ $rusak_hilang }} ? Math.abs(this.value) : 0"
                                                    value="0">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-whitesmoke text-right">
                        <button type="submit" class="btn btn-primary">
                            Konfirmasi
                        </button>
                    </div>
                </form>
            </div>
            @if (count($tagihan_peminjaman_tamus) > 0)
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
                                        @foreach ($tagihan_peminjaman_tamus as $tagihan_peminjaman_tamu)
                                            @php
                                                $rusak_hilang = $tagihan_peminjaman_tamu->rusak + $tagihan_peminjaman_tamu->hilang;
                                            @endphp
                                            <tr>
                                                <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                                <td class="align-middle">{{ $tagihan_peminjaman_tamu->nama }}</td>
                                                <td class="text-center align-middle">{{ $tagihan_peminjaman_tamu->jumlah }}
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
