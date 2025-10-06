@extends('layouts.app')

@section('title', 'Peminjaman')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('dev/peminjaman') }}" class="btn btn-secondary rounded-0">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Detail Peminjaman</h1>
        </div>
        <div class="section-body">
            <div class="card rounded-0 mb-3">
                <div class="card-header">
                    <h4>Detail Peminjaman</h4>
                    <div class="card-header-action">
                        @if ($pinjam->status == 'draft')
                            <span class="badge badge-secondary">Draft</span>
                        @elseif ($pinjam->status == 'menunggu')
                            <span class="badge badge-warning">Menunggu</span>
                        @elseif ($pinjam->status == 'disetujui')
                            <span class="badge badge-primary">Disetujui</span>
                        @elseif ($pinjam->status == 'selesai')
                            <span class="badge badge-success">Selesai</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <strong>Peminjam</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $pinjam->peminjam->nama }}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <strong>Praktik</strong>
                                </div>
                                <div class="col-md-8">
                                    @if ($pinjam->peminjam->subprodi_id != '5')
                                        @if ($pinjam->praktik_id != null)
                                            {{ $pinjam->praktik->nama }}
                                        @else
                                            Praktik Laboratorium
                                        @endif
                                    @else
                                        Praktik {{ ucfirst($pinjam->kategori) }}
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <strong>Waktu</strong>
                                </div>
                                <div class="col-md-8">
                                    @if ($pinjam->peminjam->subprodi_id != '5')
                                        @if ($pinjam->praktik_id != null)
                                            @if ($pinjam->praktik_id != '3')
                                                {{ $pinjam->jam_awal }}-{{ $pinjam->jam_akhir }} WIB,
                                                {{ date('d M Y', strtotime($pinjam->tanggal_awal)) }}
                                            @else
                                                {{ date('d M Y', strtotime($pinjam->tanggal_awal)) }} -
                                                {{ date('d M Y', strtotime($pinjam->tanggal_akhir)) }}
                                            @endif
                                        @else
                                            {{ date('d M Y', strtotime($pinjam->tanggal_awal)) }}
                                        @endif
                                    @else
                                        @if ($pinjam->kategori == 'normal')
                                            {{ date('d M Y', strtotime($pinjam->tanggal_awal)) }} -
                                            {{ date('d M Y', strtotime($pinjam->tanggal_akhir)) }}
                                        @else
                                            {{ $pinjam->jam_awal }}-{{ $pinjam->jam_akhir }} WIB,
                                            {{ date('d M Y', strtotime($pinjam->tanggal_awal)) }}
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <strong>Tempat</strong>
                                </div>
                                <div class="col-md-8">
                                    @if ($pinjam->ruang_id)
                                        {{ $pinjam->ruang->nama }}
                                    @else
                                        {{ $pinjam->keterangan }}
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <strong>Laboran</strong>
                                </div>
                                <div class="col-md-8">
                                    @if ($pinjam->ruang_id)
                                        {{ $pinjam->ruang->laboran->nama }}
                                    @else
                                        {{ $pinjam->laboran->nama }}
                                    @endif
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
                                    <strong>Prasat</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $pinjam->keterangan_praktik ?? '-' }}
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
                                    {{ $pinjam->kelas ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if (count($data_kelompok) > 0)
                <div class="card rounded-0 mb-3">
                    <div class="card-header">
                        <h4>Detail Kelompok</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($data_kelompok as $data)
                                <div class="col-md-6">
                                    <div class="row mb-2">
                                        <div class="col-md-4">
                                            <strong>Ketua</strong>
                                        </div>
                                        <div class="col-md-8">
                                            {{ $data['ketua']['kode'] }} | {{ $data['ketua']['nama'] }}
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-4">
                                            <strong>Anggota</strong>
                                        </div>
                                        <div class="col-md-8">
                                            @php
                                                $anggotas = $data['anggota'];
                                            @endphp
                                            <ul class="p-0" style="list-style: none">
                                                @foreach ($anggotas as $anggota)
                                                    <li>{{ $anggota['kode'] }} | {{ $anggota['nama'] }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
            <div class="card rounded-0 mb-3">
                <div class="card-header">
                    <h4>Detail Barang</h4>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-md">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 20px">No.</th>
                                <th>Nama Barang</th>
                                <th>Ruang</th>
                                <th class="text-center" style="width: 100px">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($detail_pinjams as $detail_pinjam)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $detail_pinjam->barang->nama }}</td>
                                    <td>{{ $detail_pinjam->barang->ruang->nama }}</td>
                                    <td class="text-center">
                                        {{ $detail_pinjam->jumlah }} Pcs
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center text-muted" colspan="4">- Data tidak ditemukan -</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
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
        </div>
    </section>
@endsection
