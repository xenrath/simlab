@extends('layouts.app')

@section('title', 'Peminjaman')

@section('content')
  <section class="section">
    <div class="section-header">
      <div class="section-header-back">
        <a href="{{ url('laboran/peminjaman-new') }}" class="btn btn-secondary">
          <i class="fas fa-arrow-left"></i>
        </a>
      </div>
      <h1>Detail Peminjaman</h1>
    </div>
    <div class="section-body">
      <div class="card">
        <div class="card-header">
          <h4>Detail Peminjaman</h4>
          <div class="card-header-action">
            @php
              $tanggal_awal = date('d/m/Y', strtotime($pinjam->tanggal_awal));
              $tanggal_akhir = date('d/m/Y', strtotime($pinjam->tanggal_akhir));
              $jam_awal = $pinjam->jam_awal;
              $jam_akhir = $pinjam->jam_akhir;
              $now = Carbon\Carbon::now();
              $expire = date('Y-m-d G:i:s', strtotime($pinjam->tanggal_awal . $jam_awal));
            @endphp
            @if ($now > $expire)
              <span class="badge badge-danger">Kadaluarsa</span>
            @else
              <span class="badge badge-warning">Menunggu</span>
            @endif
          </div>
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
                @if ($pinjam->praktik_id != '1')
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
      </div>
      @if ($pinjam->praktik_id != null)
        @if ($pinjam->praktik_id != '3')
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
      @else
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
      @endif
      <div class="card">
        <div class="card-header">
          <h4>Detail Alat</h4>
        </div>
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th class="text-center">No.</th>
                <th>Nama Alat</th>
                <th>Ruang</th>
                <th class="text-center">Jumlah</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($detail_pinjams as $detail_pinjam)
                <tr>
                  <td class="text-center">{{ $loop->iteration }}</td>
                  <td>{{ $detail_pinjam->barang->nama }}</td>
                  <td>{{ $detail_pinjam->barang->ruang->nama }}</td>
                  <td class="text-center">{{ $detail_pinjam->jumlah }} {{ ucfirst($detail_pinjam->satuan->nama) }}
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <div class="card">
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
      <div class="float-right">
        {{-- <a href="{{ url('laboran/peminjaman-new/' . $pinjam->id . '/tolak') }}" class="btn btn-danger mr-1">
          Tolak
        </a> --}}
        <a href="{{ url('laboran/peminjaman-new/' . $pinjam->id . '/setujui') }}" class="btn btn-primary">
          Setujui
        </a>
      </div>
    </div>
  </section>
@endsection
