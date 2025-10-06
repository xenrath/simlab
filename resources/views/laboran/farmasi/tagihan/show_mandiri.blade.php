@extends('layouts.app')

@section('title', 'Tagihan Peminjaman')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('laboran/farmasi/tagihan') }}" class="btn btn-secondary rounded-0">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Tagihan Peminjaman</h1>
        </div>
        <div class="section-body">
            <div class="card rounded-0 mb-3">
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
                                        {{ $pinjam->laboran->nama }}
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
                    @if ($pinjam->bahan)
                        <div class="card-body border-top">
                            <div class="row mb-2">
                                <div class="col-md-2">
                                    <strong>Bahan</strong>
                                </div>
                                <div class="col-md-10">
                                    {{ $pinjam->bahan }}
                                </div>
                            </div>
                        </div>
                    @else
                        @if (count($pinjam_detail_bahans))
                            <div class="card-body border-top">
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
                        @endif
                    @endif
                </div>
            </div>
            <div class="card rounded-0 mb-3">
                <div class="card-header">
                    <h4>Konfirmasi Pengembalian</h4>
                </div>
                <form action="{{ url('laboran/farmasi/tagihan/' . $pinjam->id) }}" method="POST" id="form-submit">
                    @csrf
                    @method('PUT')
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-md mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 20px">No</th>
                                        <th>Nama Barang</th>
                                        <th class="text-center" style="width: 140px">Rusak / Hilang</th>
                                        <th style="width: 240px">Dikembalikan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($detail_pinjams as $detail_pinjam)
                                        <tr>
                                            <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                            <td class="align-middle">
                                                <strong>{{ $detail_pinjam->barang->nama }}</strong><br>
                                                <small>({{ $detail_pinjam->barang->ruang->nama }})</small>
                                            </td>
                                            @php
                                                if (array_key_exists($detail_pinjam->id, $tagihan_detail)) {
                                                    $rusak_hilang =
                                                        $detail_pinjam->rusak +
                                                        $detail_pinjam->hilang -
                                                        $tagihan_detail[$detail_pinjam->id];
                                                } else {
                                                    $rusak_hilang = $detail_pinjam->rusak + $detail_pinjam->hilang;
                                                }
                                            @endphp
                                            <td class="text-center align-middle">{{ $rusak_hilang }} Pcs</td>
                                            <td>
                                                <input type="number" class="form-control rounded-0"
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
                        <button type="button" class="btn btn-primary rounded-0" id="btn-submit" onclick="form_submit()">
                            <div id="btn-submit-load" style="display: none;">
                                <i class="fa fa-spinner fa-spin mr-1"></i>
                                Memproses...
                            </div>
                            <span id="btn-submit-text">Konfirmasi Pengembalian</span>
                        </button>
                    </div>
                </form>
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
                                <table class="table table-striped table-bordered table-md mb-0">
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
                                            <tr>
                                                <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                                <td>
                                                    <strong>{{ $tagihan_peminjaman->detail_pinjam->barang->nama }}</strong><br>
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

@section('script')
    <script>
        function form_submit() {
            $('#btn-submit').prop('disabled', true);
            $('#btn-submit-text').hide();
            $('#btn-submit-load').show();
            $('#form-submit').submit();
        }
    </script>
@endsection
