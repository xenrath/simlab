@extends('layouts.app')

@section('title', 'Detail Data Peminjaman')

@section('content')
<section class="section">
  <div class="section-header">
    <div class="section-header-back">
      <a href="{{ url('laboran/pinjam') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
      </a>
    </div>
    <h1>Detail Pinjaman</h1>
  </div>
  <div class="section-body">
    <div class="card">
      <div class="card-header">
        <h4>Detail Peminjaman</h4>
        <div class="card-header-action">
          @if ($pinjam->status == 'menunggu')
          <span class="badge badge-warning">Menunggu</span>
          @elseif ($pinjam->status == 'diterima')
          <span class="badge badge-primary">Diterima</span>
          @elseif ($pinjam->status == 'selesai')
          <span class="badge badge-success">Selesai</span>
          @endif
        </div>
      </div>
      <div class="card-body">
        <div class="row p-0">
          <div class="col-md-6 p-0">
            <table class="table">
              <tr>
                <th class="w-25">Tanggal Pinjam</th>
                <td class="w-50">{{ $pinjam->jam_awal }}, {{ date('d-m-Y', strtotime($pinjam->tanggal_awal)) }}</td>
              </tr>
              <tr>
                <th class="w-25">Tanggal Kembali</th>
                <td class="w-50">{{ $pinjam->jam_akhir }}, {{ date('d-m-Y', strtotime($pinjam->tanggal_akhir)) }}</td>
              </tr>
              <tr>
                <th class="w-25">Ruang Lab.</th>
                <td class="w-50">{{ $pinjam->ruang->nama }}</td>
              </tr>
            </table>
          </div>
          <div class="col-md-6 p-0">
            <table class="table">
              <tr>
                <th class="w-25">Mata Kuliah</th>
                <td class="w-50">{{ $pinjam->matakuliah }}</td>
              </tr>
              <tr>
                <th class="w-25">Dosen</th>
                <td class="w-50">{{ $pinjam->dosen }}</td>
              </tr>
              <tr>
                <th class="w-25">Keterangan</th>
                <td class="w-50">
                  @if ($pinjam->keterangan)
                  {{ $pinjam->keterangan }}</td>
                @else
                -
                @endif
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-header">
        <h4>Detail Kelompok</h4>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive-sm">
          <table class="table table-hover">
            <thead>
              <tr>
                <th class="text-center">No.</th>
                <th>Nama Kelompok</th>
                <th>Ketua</th>
                <th>Anggota</th>
              </tr>
            </thead>
            <tbody>
              @foreach($kelompoks as $kelompok)
              <tr>
                <td class="text-center align-top py-3">{{ $loop->iteration }}</td>
                <td class="align-top py-3">{{ $kelompok->nama }}</td>
                <td class="align-top py-3">{{ $kelompok->m_ketua->nama }}</td>
                <td class="py-3">
                  @foreach ($kelompok->anggota as $anggota)
                  <span class="bullet"></span>&nbsp;{{ App\Models\User::where('kode', $anggota)->first()->nama }}
                  <br>
                  @endforeach
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
        <h4>Detail Barang</h4>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive-sm">
          <table class="table table-hover">
            <thead>
              <tr>
                <th class="text-center">No.</th>
                <th>Nama Barang</th>
                <th class="text-center">Jumlah</th>
              </tr>
            </thead>
            <tbody>
              @foreach($detailpinjams as $detailpinjam)
              <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $detailpinjam->barang->nama }}</td>
                <td class="text-center">{{ $detailpinjam->jumlah }} {{ $detailpinjam->satuan->singkatan }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection