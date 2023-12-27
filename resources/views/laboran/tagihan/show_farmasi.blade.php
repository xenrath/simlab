@extends('layouts.app')

@section('title', 'Konfirmasi Tagihan')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('laboran/tagihan') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Konfirmasi Tagihan</h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Detail Peminjaman</h4>
                    <div class="card-header-action">
                        <div class="card-header-action">
                            <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#">
                                <i class="fas fa-plus"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="collapse" id="mycard-collapse">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <strong>Peminjam</strong>
                                    </div>
                                    <div class="col-md-8">
                                        {{ $pinjam->peminjam->nama }}
                                    </div>
                                </div>
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
                                        {{ $pinjam->praktik->nama }} ({{ $kategori }})
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <strong>Waktu</strong>
                                    </div>
                                    <div class="col-md-8">
                                        {{ date('d M Y', strtotime($pinjam->tanggal_awal)) }} -
                                        {{ date('d M Y', strtotime($pinjam->tanggal_awal)) }}
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
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <strong>Ruang Lab.</strong>
                                    </div>
                                    <div class="col-md-8">
                                        {{ $pinjam->ruang->nama }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if ($pinjam->kategori == 'estafet')
                            <hr class="mt-0">
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
                        @endif
                        <hr class="mt-0">
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
            <div class="card">
                <div class="card-header">
                    <h4>Konfirmasi Pengembalian</h4>
                </div>
                <form action="{{ url('laboran/tagihan/konfirmasi/' . $pinjam->id) }}" method="POST">
                    @csrf
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
                                    @foreach ($detail_pinjams as $detail_pinjam)
                                        <tr>
                                            <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                            <td class="align-middle">
                                                <strong>{{ $detail_pinjam->barang_nama }}</strong><br>
                                                <small>({{ $detail_pinjam->ruang_nama }})</small>
                                            </td>
                                            @php
                                                if (array_key_exists($detail_pinjam->id, $tagihan_detail)) {
                                                    $rusak_hilang = $detail_pinjam->rusak + $detail_pinjam->hilang - $tagihan_detail[$detail_pinjam->id];
                                                } else {
                                                    $rusak_hilang = $detail_pinjam->rusak + $detail_pinjam->hilang;
                                                }
                                            @endphp
                                            <td class="text-center align-middle">{{ $rusak_hilang }}</td>
                                            <td>
                                                <input type="number" class="form-control"
                                                    name="jumlah[{{ $detail_pinjam->id }}]"
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
                                            @php
                                                $rusak_hilang = $tagihan_peminjaman->rusak + $tagihan_peminjaman->hilang;
                                            @endphp
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
        </div>
    </section>
@endsection