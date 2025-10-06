@extends('layouts.app')

@section('title', 'Peminjaman Menunggu')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('laboran/k3/peminjaman') }}" class="btn btn-secondary rounded-0">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Peminjaman Menunggu</h1>
        </div>
        <div class="section-body">
            <div class="text-right mb-3">
                <form action="{{ url('laboran/k3/peminjaman/setujui/' . $pinjam->id) }}" method="get"
                    id="form-konfirmasi">
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
                                    <strong>Waktu</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $pinjam->jam_awal }} - {{ $pinjam->jam_akhir }},
                                    {{ date('d M Y', strtotime($pinjam->tanggal_awal)) }}
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
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <strong>Laboran</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $pinjam->ruang->laboran->nama }}
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
            </div>
            @if (count($data_kelompok))
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
                    <form action="{{ url('laboran/k3/peminjaman/' . $pinjam->id) }}" method="POST" id="form-hapus">
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
