@extends('layouts.app')

@section('title', 'Pengembalian')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('laboran/pengembalian-new') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Pengembalian</h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Detail Peminjaman</h4>
                    <div class="card-header-action">
                        <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i
                                class="fas fa-plus"></i></a>
                    </div>
                </div>
                <div class="collapse" id="mycard-collapse">
                    <div class="card-body">
                        @if ($praktik_id == 1)
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <strong>Praktik</strong>
                                        </div>
                                        <div class="col-md-8">
                                            {{ $pinjam->praktik_nama }}
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <strong>Waktu</strong>
                                        </div>
                                        <div class="col-md-8">
                                            {{ $pinjam->jam_awal }} - {{ $pinjam->jam_akhir }},
                                            {{ date('d M Y', strtotime($pinjam->tanggal_awal)) }}
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <strong>Ruang (Lab)</strong>
                                        </div>
                                        <div class="col-md-8">
                                            {{ $pinjam->ruang_nama }}
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <strong>Laboran</strong>
                                        </div>
                                        <div class="col-md-8">
                                            {{ $pinjam->laboran_nama }}
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
                                            <strong>Praktik</strong>
                                        </div>
                                        <div class="col-md-8">
                                            {{ $pinjam->praktik }}
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
                                            <strong>Kelas</strong>
                                        </div>
                                        <div class="col-md-8">
                                            {{ $pinjam->kelas }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
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
                            @if ($pinjam->bahan)
                                <hr>
                                <div class="row mb-3">
                                    <div class="col-md-2">
                                        <strong>Bahan</strong>
                                    </div>
                                    <div class="col-md-10">
                                        {{ $pinjam->bahan }}
                                    </div>
                                </div>
                            @endif
                        @elseif ($praktik_id == 2)
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <strong>Praktik</strong>
                                        </div>
                                        <div class="col-md-8">
                                            {{ $pinjam->praktik_nama }}
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <strong>Waktu</strong>
                                        </div>
                                        <div class="col-md-8">
                                            {{ $pinjam->jam_awal }} - {{ $pinjam->jam_akhir }},
                                            {{ date('d M Y', strtotime($pinjam->tanggal_awal)) }}
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <strong>Ruang Kelas</strong>
                                        </div>
                                        <div class="col-md-8">
                                            {{ $pinjam->keterangan }}
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <strong>Laboran Penerima</strong>
                                        </div>
                                        <div class="col-md-8">
                                            {{ $pinjam->laboran_nama }}
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
                                            <strong>Praktik</strong>
                                        </div>
                                        <div class="col-md-8">
                                            {{ $pinjam->praktik }}
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
                                            <strong>Kelas</strong>
                                        </div>
                                        <div class="col-md-8">
                                            {{ $pinjam->kelas }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
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
                            @if ($pinjam->bahan)
                                <hr>
                                <div class="row mb-3">
                                    <div class="col-md-2">
                                        <strong>Bahan</strong>
                                    </div>
                                    <div class="col-md-10">
                                        {{ $pinjam->bahan }}
                                    </div>
                                </div>
                            @endif
                        @elseif ($praktik_id == 3)
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <strong>Praktik</strong>
                                        </div>
                                        <div class="col-md-8">
                                            {{ $pinjam->praktik_nama }}
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
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <strong>Laboran Penerima</strong>
                                        </div>
                                        <div class="col-md-8">
                                            {{ $pinjam->laboran_nama }}
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <strong>Klinik / Rumah Sakit</strong>
                                        </div>
                                        <div class="col-md-8">
                                            {{ $pinjam->keterangan }}
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
                                            <strong>Praktik</strong>
                                        </div>
                                        <div class="col-md-8">
                                            {{ $pinjam->praktik }}
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
                                            <strong>Kelas</strong>
                                        </div>
                                        <div class="col-md-8">
                                            {{ $pinjam->kelas }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if ($pinjam->bahan)
                                <hr>
                                <div class="row mb-3">
                                    <div class="col-md-2">
                                        <strong>Bahan</strong>
                                    </div>
                                    <div class="col-md-10">
                                        {{ $pinjam->bahan }}
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
            @if (session('errors'))
                <div class="alert alert-danger alert-has-icon alert-dismissible show fade">
                    <div class="alert-body">
                        <div class="alert-title">GAGAL !</div>
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
            <div class="card">
                <div class="card-header">
                    <h4>Konfirmasi Pengembalian</h4>
                    <small style="line-height: 1.5">(kosongkan saja jika tidak ada barang rusak / hilang)</small>
                </div>
                <form action="{{ url('laboran/pengembalian-new/' . $pinjam->id . '/p_konfirmasi') }}" method="POST">
                    @csrf
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-md">
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
                                    @forelse($detail_pinjams as $detail_pinjam)
                                        @php
                                            $rusak = 0;
                                            $hilang = 0;
                                            if (session('datas')) {
                                                $rusak = session('datas')[$detail_pinjam->id]['rusak'];
                                                $hilang = session('datas')[$detail_pinjam->id]['hilang'];
                                            }
                                        @endphp
                                        <tr>
                                            <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                            <td class="align-middle">{{ $detail_pinjam->nama }}</td>
                                            <td class="align-middle text-center">{{ $detail_pinjam->jumlah }}</td>
                                            @php
                                                $jumlah = $detail_pinjam->jumlah;
                                            @endphp
                                            <td>
                                                <input type="number" name="rusak-{{ $detail_pinjam->id }}"
                                                    class="form-control"
                                                    oninput="this.value = !!this.value && Math.abs(this.value) >= 0 && !!this.value && Math.abs(this.value) <= {{ $jumlah }} ? Math.abs(this.value) : 0"
                                                    value="{{ $rusak }}">
                                            </td>
                                            <td>
                                                <input type="number" name="hilang-{{ $detail_pinjam->id }}"
                                                    class="form-control"
                                                    oninput="this.value = !!this.value && Math.abs(this.value) >= 0 && !!this.value && Math.abs(this.value) <= {{ $jumlah }} ? Math.abs(this.value) : 0"
                                                    value="{{ $hilang }}">
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
                    <div class="card-footer text-right">
                        <button type="reset" class="btn btn-secondary mr-1">
                            Reset
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Konfirmasi
                        </button>
                    </div>
                </form>
            </div>
            {{-- <div class="card">
                <div class="card-header">
                    <h4>Detail Peminjaman</h4>
                </div>
                <div class="card-body">
                    @if ($pinjam->praktik_id != null)
                        <div class="row">
                            <div class="col-md-6">
                                @if ($pinjam->praktik_id == '3')
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <strong>Peminjam</strong>
                                        </div>
                                        <div class="col-md-8">
                                            {{ $pinjam->peminjam->nama }}
                                        </div>
                                    </div>
                                @endif
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <strong>Praktik</strong>
                                    </div>
                                    <div class="col-md-8">
                                        {{ $pinjam->praktik->nama }}
                                    </div>
                                </div>
                                @if ($pinjam->praktik_id == '1')
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <strong>Waktu</strong>
                                        </div>
                                        <div class="col-md-8">
                                            {{ $pinjam->jam_awal }} - {{ $pinjam->jam_akhir }},
                                            {{ date('d M Y', strtotime($pinjam->tanggal_awal)) }}
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <strong>Ruang (Lab)</strong>
                                        </div>
                                        <div class="col-md-8">
                                            {{ $pinjam->ruang->nama }}
                                        </div>
                                    </div>
                                @elseif ($pinjam->praktik_id == '2')
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <strong>Waktu</strong>
                                        </div>
                                        <div class="col-md-8">
                                            {{ $pinjam->jam_awal }} - {{ $pinjam->jam_akhir }},
                                            {{ date('d M Y', strtotime($pinjam->tanggal_awal)) }}
                                        </div>
                                    </div>
                                @elseif ($pinjam->praktik_id == '3')
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <strong>Waktu</strong>
                                        </div>
                                        <div class="col-md-8">
                                            {{ date('d M Y', strtotime($pinjam->tanggal_awal)) }} -
                                            {{ date('d M Y', strtotime($pinjam->tanggal_akhir)) }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <strong>Mata Kuliah - Praktik</strong>
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
                                @if ($pinjam->praktik_id == '1')
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <strong>Kelas</strong>
                                        </div>
                                        <div class="col-md-8">
                                            {{ $pinjam->keterangan }}
                                        </div>
                                    </div>
                                @else
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <strong>Keterangan</strong>
                                        </div>
                                        <div class="col-md-8">
                                            {{ $pinjam->keterangan }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-md-6">
                                @if (!$pinjam->kelompoks->first()->anggota)
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <strong>Peminjam</strong>
                                        </div>
                                        <div class="col-md-8">
                                            {{ $pinjam->peminjam->nama }}
                                        </div>
                                    </div>
                                @endif
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <strong>Waktu Pinjam</strong>
                                    </div>
                                    <div class="col-md-8">
                                        {{ $pinjam->jam_awal }}, {{ date('d M Y', strtotime($pinjam->tanggal_awal)) }}
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <strong>Waktu Kembali</strong>
                                    </div>
                                    <div class="col-md-8">
                                        {{ $pinjam->jam_akhir }}, {{ date('d M Y', strtotime($pinjam->tanggal_akhir)) }}
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
                                        <strong>Keterangan</strong>
                                    </div>
                                    <div class="col-md-8">
                                        @if ($pinjam->keterangan)
                                            {{ $pinjam->keterangan }}
                                        @else
                                            -
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                @if ($pinjam->praktik_id != null)
                    @if ($pinjam->praktik_id != '3')
                        <hr>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <strong>Ketua</strong>
                                        </div>
                                        <div class="col-md-8">
                                            {{ $pinjam->kelompoks->first()->m_ketua->nama }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong>Anggota</strong>
                                        </div>
                                        <div class="col-md-8">
                                            @php
                                                $kelompok = $pinjam->kelompoks->first();
                                            @endphp
                                            @foreach ($kelompok->anggota as $anggota)
                                                <span
                                                    class="bullet"></span>&nbsp;{{ App\Models\User::where('kode', $anggota)->first()->nama }}
                                                <br>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    @if ($pinjam->kelompoks->first()->anggota)
                        <hr>
                        <div class="card">
                            <div class="card-header">
                                <h4>Detail Kelompok</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <strong>Ketua</strong>
                                            </div>
                                            <div class="col-md-8">
                                                {{ $pinjam->kelompoks->first()->m_ketua->nama }}
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <strong>Anggota</strong>
                                            </div>
                                            <div class="col-md-8">
                                                @php
                                                    $kelompok = $pinjam->kelompoks->first();
                                                @endphp
                                                @foreach ($kelompok->anggota as $anggota)
                                                    <span
                                                        class="bullet"></span>&nbsp;{{ App\Models\User::where('kode', $anggota)->first()->nama }}
                                                    <br>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
                <hr>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label for="bahan">
                                <strong>Bahan</strong>
                            </label>
                        </div>
                        <div class="col-md-10">
                            {{ $pinjam->bahan }}
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </section>
@endsection
