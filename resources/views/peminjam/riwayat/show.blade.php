@extends('layouts.app')

@section('title', 'Detail Peminjaman')

@section('content')
  <section class="section">
    <div class="section-header">
      <div class="section-header-back">
        <a href="{{ url('peminjam/normal/riwayat') }}" class="btn btn-secondary">
          <i class="fas fa-arrow-left"></i>
        </a>
      </div>
      <h1>Peminjaman</h1>
    </div>
    <div class="section-body">
      <div class="card">
        <div class="card-header">
          <h4>Detail Peminjaman</h4>
          <div class="card-header-action">
            <span class="badge badge-success">Selesai</span>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
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
              <div class="row mb-3">
                <div class="col-md-4">
                  <strong>Laboran</strong>
                </div>
                <div class="col-md-8">
                  {{ $pinjam->ruang->laboran->nama }}
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
        </div>
      </div>
      @if ($pinjam->kelompoks->first()->anggota)
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
                      <span class="bullet"></span>&nbsp;{{ App\Models\User::where('kode', $anggota)->first()->nama }}
                      <br>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endif
      <div class="card">
        <div class="card-header">
          <h4>Detail Alat</h4>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th class="text-center">No.</th>
                  <th>Alat</th>
                  <th>Ruang</th>
                  <th class="text-center">Jumlah</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($detailpinjams as $detailpinjam)
                  <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $detailpinjam->barang->nama }}</td>
                    <td>{{ $detailpinjam->barang->ruang->nama }}</td>
                    <td class="text-center">{{ $detailpinjam->jumlah }} {{ ucfirst($detailpinjam->satuan->nama) }}
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-header">
          <h4>Detail Bahan</h4>
        </div>
        <div class="card-body">
          <div class="row">
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
