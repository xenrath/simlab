@extends('layouts.app')

@section('title', 'Peminjaman Menunggu')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('laboran/farmasi/peminjaman') }}" class="btn btn-secondary rounded-0">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Peminjaman Menunggu</h1>
        </div>
        <div class="section-body">
            <div class="text-right mb-3">
                <form action="{{ url('laboran/farmasi/peminjaman/setujui/' . $pinjam->id) }}" method="get" id="form-konfirmasi">
                    <button type="button" class="btn btn-danger rounded-0" data-toggle="modal" data-target="#modal-hapus">
                        Hapus
                    </button>
                    <span class="mx-2">|</span>
                    <button type="button" class="btn btn-primary rounded-0" id="btn-konfirmasi"
                        onclick="form_konfirmasi()">
                        <div id="btn-konfirmasi-load" style="display: none;">
                            <i class="fa fa-spinner fa-spin mr-1"></i>
                            Memproses...
                        </div>
                        <span id="btn-konfirmasi-text">Konfirmasi</span>
                    </button>
                </form>
            </div>
            <div class="card rounded-0 mb-3">
                <div class="card-header">
                    <h4>Detail Peminjaman</h4>
                    <div class="card-header-action">
                        @php
                            $now = Carbon\Carbon::now()->format('Y-m-d');
                            $expire = date('Y-m-d', strtotime($pinjam->tanggal_akhir));
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
                                    <strong>Tanggal</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ Carbon\Carbon::parse($pinjam->tanggal_awal)->translatedFormat('d F Y') }}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <strong>Jam Praktik</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $pinjam->jam_awal }}-{{ $pinjam->jam_akhir }} WIB
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <strong>Ruang Lab</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $pinjam->ruang->nama }}
                                </div>
                            </div>
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
                        </div>
                    </div>
                </div>
            </div>
            <div class="card rounded-0 mb-3">
                <div class="card-header">
                    <h4>Detail Kelompok</h4>
                </div>
                <div class="card-body">
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
            </div>
            <div class="card rounded-0 mb-3">
                <div class="card-header">
                    <h4>Detail Barang</h4>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered table-striped table-md mb-0">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 20px">No.</th>
                                <th>Nama Barang</th>
                                <th class="text-center" style="width: 100px">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($detail_pinjams as $detail_pinjam)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $detail_pinjam->barang->nama }}</strong><br>
                                        <small>({{ $detail_pinjam->barang->ruang->nama }})</small>
                                    </td>
                                    <td class="text-center">{{ $detail_pinjam->jumlah }} Pcs</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($pinjam->bahan)
                <div class="card rounded-0 mb-3">
                    <div class="card-header">
                        <h4>Detail Bahan</h4>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <strong>Bahan</strong>
                            </div>
                            <div class="col-md-10">
                                {{ $pinjam->bahan }}
                            </div>
                        </div>
                    </div>
                </div>
            @else
                @if (count($pinjam_detail_bahans))
                    <div class="card rounded-0 mb-3">
                        <div class="card-header">
                            <h4>List Bahan</h4>
                        </div>
                        <div class="card-body p-0">
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
                    </div>
                @endif
            @endif
        </div>
    </section>
    <div class="modal fade" tabindex="-1" role="dialog" id="modal-hapus">
        <div class="modal-dialog" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Peminjaman</h5>
                </div>
                <div class="modal-body">
                    <span>Apakah anda yakin akan menghapus peminjaman ini?</span>
                </div>
                <div class="modal-footer bg-whitesmoke justify-content-between">
                    <button type="button" class="btn btn-secondary rounded-0" data-dismiss="modal">Batal</button>
                    <form action="{{ url('laboran/farmasi/peminjaman/' . $pinjam->id) }}" method="POST" id="form-hapus">
                        @csrf
                        @method('delete')
                        <button type="button" class="btn btn-danger rounded-0" id="btn-hapus" onclick="form_hapus()">
                            <div id="btn-hapus-load" style="display: none;">
                                <i class="fa fa-spinner fa-spin mr-1"></i>
                                Memproses...
                            </div>
                            <span id="btn-hapus-text">Hapus</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function form_hapus() {
            $('#btn-hapus').prop('disabled', true);
            $('#btn-hapus-text').hide();
            $('#btn-hapus-load').show();
            $('#form-hapus').submit();
        }

        function form_konfirmasi() {
            $('#btn-konfirmasi').prop('disabled', true);
            $('#btn-konfirmasi-text').hide();
            $('#btn-konfirmasi-load').show();
            $('#form-konfirmasi').submit();
        }
    </script>
@endsection
