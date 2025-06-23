@extends('layouts.app')

@section('title', 'Tagihan Peminjaman')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('admin/tagihan') }}" class="btn btn-secondary rounded-0">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Tagihan Peminjaman</h1>
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
                                    <strong>Nama Tamu</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $peminjaman_tamu->tamu->nama ?? '-' }}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <strong>Asal Institusi</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $peminjaman_tamu->tamu->institusi ?? '-' }}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <strong>No. Telepon</strong>
                                </div>
                                <div class="col-md-8">
                                    @if ($peminjaman_tamu->tamu)
                                        <a href="{{ url('admin/hubungi_tamu/' . $peminjaman_tamu->tamu_id) }}"
                                            target="_blank">
                                            +62{{ $peminjaman_tamu->tamu->telp }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <strong>Alamat</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $peminjaman_tamu->tamu->alamat ?? '-' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <strong>Waktu Peminjaman</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ date('d M Y', strtotime($peminjaman_tamu->tanggal_awal)) }} -
                                    {{ date('d M Y', strtotime($peminjaman_tamu->tanggal_akhir)) }}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <strong>Lama</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $peminjaman_tamu->lama }} Hari
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <strong>Keperluan</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $peminjaman_tamu->keperluan ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card rounded-0 mb-3">
                <div class="card-header">
                    <h4>Konfirmasi Pengembalian</h4>
                </div>
                <form action="{{ url('admin/tagihan/' . $peminjaman_tamu->id) }}" method="POST" autocomplete="off"
                    id="form-submit">
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
                                    @foreach ($detail_peminjaman_tamus as $detail_peminjaman_tamu)
                                        <tr>
                                            <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                            <td class="align-middle">
                                                <strong>{{ $detail_peminjaman_tamu->barang->nama }}</strong><br>
                                                <small>({{ $detail_peminjaman_tamu->barang->ruang->nama }})</small>
                                            </td>
                                            @php
                                                if (array_key_exists($detail_peminjaman_tamu->id, $tagihan_detail)) {
                                                    $rusak_hilang =
                                                        $detail_peminjaman_tamu->rusak +
                                                        $detail_peminjaman_tamu->hilang -
                                                        $tagihan_detail[$detail_peminjaman_tamu->id];
                                                } else {
                                                    $rusak_hilang =
                                                        $detail_peminjaman_tamu->rusak +
                                                        $detail_peminjaman_tamu->hilang;
                                                }
                                            @endphp
                                            <td class="text-center align-middle">{{ $rusak_hilang }} Pcs</td>
                                            <td>
                                                <input type="number" class="form-control rounded-0"
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
            @if (count($tagihan_peminjaman_tamus))
                <div class="card rounded-0 mb-3">
                    <div class="card-header">
                        <h4>Riwayat Tagihan</h4>
                        <div class="card-header-action">
                            <a data-collapse="#card-tagihan" class="btn btn-icon btn-info" href="#">
                                <i class="fas fa-plus"></i>
                            </a>
                        </div>
                    </div>
                    <div class="collapse" id="card-tagihan">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-md mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 20px">No</th>
                                            <th>Nama Barang</th>
                                            <th class="text-center" style="width: 100px">Jumlah</th>
                                            <th style="width: 160px">Tanggal Kembali</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tagihan_peminjaman_tamus as $tagihan_peminjaman_tamu)
                                            <tr>
                                                <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                                <td>
                                                    <strong>{{ $tagihan_peminjaman_tamu->detail_peminjaman_tamu->barang->nama }}</strong><br>
                                                    <small>({{ $tagihan_peminjaman_tamu->detail_peminjaman_tamu->barang->ruang->nama }})</small>
                                                </td>
                                                <td class="text-center align-middle">
                                                    {{ $tagihan_peminjaman_tamu->jumlah }} Pcs
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
