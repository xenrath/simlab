@extends('layouts.app')

@section('title', 'Dalam Peminjaman')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('laboran/k3/pengembalian') }}" class="btn btn-secondary rounded-0">
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
                            <i class="fas fa-plus"></i>
                        </a>
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
                                        <strong>Waktu</strong>
                                    </div>
                                    <div class="col-md-8">
                                        {{ Carbon\Carbon::parse($pinjam->tanggal_awal)->translatedFormat('d F Y') }},
                                        {{ $pinjam->jam_awal }}-{{ $pinjam->jam_akhir }} WIB
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-4">
                                        <strong>Ruang Kelas</strong>
                                    </div>
                                    <div class="col-md-8">
                                        {{ $pinjam->keterangan }}
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-4">
                                        <strong>Laboran Penerima</strong>
                                    </div>
                                    <div class="col-md-8">
                                        {{ $pinjam->laboran->nama }}
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
                    @if (count($data_kelompok))
                        <div class="card-body pb-2 border-top">
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
                    @endif
                </div>
            </div>
            <div class="card rounded-0 mb-3">
                <div class="card-header">
                    <h4>Form Pengembalian</h4>
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
                    @if (session('id'))
                        <div class="alert alert-danger alert-dismissible show fade rounded-0">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                                <span>Pastikan pilih pelaku jika ada barang yang rusak maupun hilang!</span>
                            </div>
                        </div>
                    @endif
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
                <form action="{{ url('laboran/k3/pengembalian/' . $pinjam->id) }}" method="POST" id="form-submit">
                    @csrf
                    @method('PUT')
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-md mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 20px">No</th>
                                        <th>Nama Barang</th>
                                        <th class="text-center" style="width: 100px">Jumlah</th>
                                        <th style="width: 180px">Rusak</th>
                                        <th style="width: 180px">Hilang</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($detail_pinjams as $detail_pinjam)
                                        <tr>
                                            <td class="text-center align-top">{{ $loop->iteration }}</td>
                                            <td>
                                                <strong>{{ $detail_pinjam->barang->nama }}</strong><br>
                                                <small>({{ $detail_pinjam->barang->ruang->nama }})</small>
                                            </td>
                                            <td class="text-center">{{ $detail_pinjam->jumlah }} Pcs</td>
                                            <td>
                                                <input type="number" name="rusak[{{ $detail_pinjam->id }}]"
                                                    class="form-control rounded-0"
                                                    oninput="this.value = !!this.value && Math.abs(this.value) >= 0 && !!this.value && Math.abs(this.value) <= {{ $detail_pinjam->jumlah }} ? Math.abs(this.value) : 0"
                                                    value="{{ old('rusak')[$detail_pinjam->id] ?? '0' }}">
                                            </td>
                                            <td>
                                                <input type="number" name="hilang[{{ $detail_pinjam->id }}]"
                                                    class="form-control rounded-0"
                                                    oninput="this.value = !!this.value && Math.abs(this.value) >= 0 && !!this.value && Math.abs(this.value) <= {{ $detail_pinjam->jumlah }} ? Math.abs(this.value) : 0"
                                                    value="{{ old('hilang')[$detail_pinjam->id] ?? '0' }}">
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center" colspan="5">- Tidak ada barang yang dipinjam -</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-whitesmoke text-right">
                        <button type="button" class="btn btn-primary rounded-0" id="btn-submit"
                            onclick="form_submit()">
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
