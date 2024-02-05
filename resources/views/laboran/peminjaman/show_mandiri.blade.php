@extends('layouts.app')

@section('title', 'Peminjaman Menunggu')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('laboran/peminjaman') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Peminjaman Menunggu</h1>
        </div>
        <div class="section-body">
            <div class="text-right mb-3">
                <button class="btn btn-danger" data-confirm="Hapus Peminjaman|Apakah anda yakin menghapus peminjaman ini?"
                    data-confirm-yes="modalDelete({{ $pinjam->id }})">
                    Hapus
                </button>
                <span class="mx-2">|</span>
                <a href="{{ url('laboran/peminjaman/setujui/' . $pinjam->id) }}" class="btn btn-primary">
                    Setujui
                </a>
                <form action="{{ url('laboran/peminjaman/' . $pinjam->id) }}" method="POST"
                    id="delete-{{ $pinjam->id }}">
                    @csrf
                    @method('delete')
                </form>
            </div>
            <div class="card mb-3">
                <div class="card-header">
                    <h4>Detail Peminjaman</h4>
                    <div class="card-header-action">
                        @php
                            $now = Carbon\Carbon::now()->format('Y-m-d');
                            $expire = date('Y-m-d', strtotime($pinjam->tanggal_awal));
                        @endphp
                        @if ($now > $expire)
                            <span class="badge badge-danger">Kadaluarsa</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
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
                                <div class="col-md-8">
                                    Praktik Mandiri
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
                                    <strong>Ruang Lab</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $pinjam->ruang->nama }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header">
                    <h4>Detail Barang</h4>
                </div>
                <table class="table table-striped table-bordered table-md">
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
                                    <strong>{{ $detail_pinjam->barang_nama }}</strong><br>
                                    <small>({{ $detail_pinjam->ruang_nama }})</small>
                                </td>
                                <td class="text-center">{{ $detail_pinjam->jumlah }} Pcs
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($pinjam->bahan)
                <div class="card mb-3">
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
            @endif
        </div>
    </section>
    <script>
        function modalDelete(id) {
            $("#delete-" + id).submit();
        }
    </script>
@endsection
