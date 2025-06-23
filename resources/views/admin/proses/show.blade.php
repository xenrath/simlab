@extends('layouts.app')

@section('title', 'Dalam Peminjaman')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('admin/proses') }}" class="btn btn-secondary rounded-0">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Dalam Peminjaman</h1>
        </div>
        <div class="section-body">
            <div class="card rounded-0 mb-3">
                <div class="card-header">
                    <h4>Detail Peminjaman</h4>
                    <div class="card-header-action">
                        <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#">
                            <i class="fas fa-minus"></i>
                        </a>
                    </div>
                </div>
                <div class="collapse" id="mycard-collapse">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row mb-2">
                                    <div class="col-md-4">
                                        <strong>Nama Tamu</strong>
                                    </div>
                                    <div class="col-md-8">
                                        {{ $peminjaman_tamu->tamu->nama }}
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-4">
                                        <strong>Asal Institusi</strong>
                                    </div>
                                    <div class="col-md-8">
                                        {{ $peminjaman_tamu->tamu->institusi }}
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-4">
                                        <strong>No. Telepon</strong>
                                    </div>
                                    <div class="col-md-8">
                                        <a href="{{ url('admin/hubungi_tamu/' . $peminjaman_tamu->tamu_id) }}"
                                            target="_blank">
                                            +62{{ $peminjaman_tamu->tamu->telp }}
                                        </a>
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
            </div>
            <div class="card rounded-0 mb-3">
                <div class="card-header">
                    <h4>Form Pengembalian</h4>
                    <small style="line-height: 1.5">(kosongkan saja jika tidak ada barang rusak / hilang)</small>
                </div>
                <div class="card-body pb-2">
                    <div class="alert alert-info alert-dismissible show fade rounded-0">
                        <div class="alert-body">
                            <button class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                            <span>
                                <strong>Kosongkan</strong> saja jika tidak ada barang rusak maupun hilang
                            </span>
                        </div>
                    </div>
                    @if (session('errors'))
                        <div class="alert alert-danger alert-dismissible show fade rounded-0">
                            <div class="alert-body">
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
                </div>
                <form action="{{ url('admin/proses/' . $peminjaman_tamu->id) }}" method="POST" id="form-submit">
                    @csrf
                    @method('PUT')
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
                                        <tr>
                                            <td class="text-center align-top">{{ $loop->iteration }}</td>
                                            <td>
                                                <strong>{{ $detail_peminjaman_tamu->barang->nama }}</strong><br>
                                                <small>({{ $detail_peminjaman_tamu->barang->ruang->nama }})</small>
                                            </td>
                                            <td class="text-center">{{ $detail_peminjaman_tamu->total }} Pcs</td>
                                            @php
                                                $total = $detail_peminjaman_tamu->total;
                                            @endphp
                                            <td>
                                                <input type="number" name="rusak[{{ $detail_peminjaman_tamu->id }}]"
                                                    class="form-control rounded-0"
                                                    oninput="this.value = !!this.value && Math.abs(this.value) >= 0 && !!this.value && Math.abs(this.value) <= {{ $detail_peminjaman_tamu->total }} ? Math.abs(this.value) : 0"
                                                    value="{{ old('rusak')[$detail_peminjaman_tamu->id] ?? '0' }}">
                                            </td>
                                            <td>
                                                <input type="number" name="hilang[{{ $detail_peminjaman_tamu->id }}]"
                                                    class="form-control rounded-0"
                                                    oninput="this.value = !!this.value && Math.abs(this.value) >= 0 && !!this.value && Math.abs(this.value) <= {{ $detail_peminjaman_tamu->total }} ? Math.abs(this.value) : 0"
                                                    value="{{ old('hilang')[$detail_peminjaman_tamu->id] ?? '0' }}">
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
