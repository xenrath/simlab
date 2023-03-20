@extends('layouts.app')

@section('title', 'Pengembalian')

@section('content')
  <section class="section">
    <div class="section-header">
      <div class="section-header-back">
        <a href="{{ url('laboran/rusak') }}" class="btn btn-secondary">
          <i class="fas fa-arrow-left"></i>
        </a>
      </div>
      <h1>Pengembalian</h1>
    </div>
    <div class="section-body">
      <div class="card">
        <div class="card-header">
          <h4>Konfirmasi Pengembalian</h4>
        </div>
        <form action="{{ url('laboran/rusak/' . $pinjam->id . '/konfirmasi') }}" method="POST">
          @csrf
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th class="text-center">#</th>
                    <th>Nama Alat</th>
                    <th>Jumlah</th>
                    <th>Kategori</th>
                    <th>Kembali</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($rusaks as $rusak)
                    <tr>
                      <td class="text-center">#</td>
                      <td>{{ $rusak->barang->nama }}</td>
                      <td>
                        {{ $rusak->rusak }}
                        {{ ucfirst($rusak->satuan->nama) }}
                      </td>
                      <td>Rusak</td>
                      @php
                        $jumlah = $rusak->rusak;
                      @endphp
                      <td>
                        <input type="number" name="rusak-{{ $rusak->id }}" class="form-control"
                          oninput="this.value = !!this.value && Math.abs(this.value) >= 0 && !!this.value && Math.abs(this.value) <= {{ $jumlah }} ? Math.abs(this.value) : null"
                          value="{{ old('rusak-' . $rusak->id, $rusak->rusak) }}">
                      </td>
                    </tr>
                  @endforeach
                  @foreach ($hilangs as $hilang)
                    <tr>
                      <td class="text-center">#</td>
                      <td>{{ $hilang->barang->nama }}</td>
                      <td>
                        {{ $hilang->hilang }}
                        {{ ucfirst($hilang->satuan->nama) }}
                      </td>
                      <td>Hilang</td>
                      @php
                        $jumlah = $hilang->hilang;
                      @endphp
                      <td>
                        <input type="number" name="hilang-{{ $hilang->id }}" class="form-control"
                          oninput="this.value = !!this.value && Math.abs(this.value) >= 0 && !!this.value && Math.abs(this.value) <= {{ $jumlah }} ? Math.abs(this.value) : null"
                          value="{{ old('hilang-' . $hilang->id, $hilang->hilang) }}">
                      </td>
                    </tr>
                  @endforeach
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
      <div class="card">
        <div class="card-header">
          <h4>Detail Peminjaman</h4>
        </div>
        <div class="card-body">
          @if ($pinjam->praktik_id != null)
            @if ($pinjam->praktik_id == '1' && count($pinjam->kelompoks) == 0)
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
                      <strong>Waktu</strong>
                    </div>
                    <div class="col-md-8">
                      {{ date('d M Y', strtotime($pinjam->tanggal_awal)) }} -
                      {{ date('d M Y', strtotime($pinjam->tanggal_akhir)) }}
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
            @endif
          @elseif ($pinjam->kategori == 'estafet')
            <div class="row">
              <div class="col-md-6">
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
          @if ($pinjam->praktik_id != '3' && count($pinjam->kelompoks) > 0)
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
                        <span class="bullet"></span>&nbsp;{{ App\Models\User::where('kode', $anggota)->first()->nama }}
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
                  @foreach ($pinjam->kelompoks as $kelompok)
                    <div class="col-md-6 border-bottom mb-3">
                      <div class="row mb-3">
                        <div class="col-md-4">
                          <strong>Nama Kelompok</strong>
                        </div>
                        <div class="col-md-8">
                          {{ $kelompok->nama }}
                        </div>
                      </div>
                      <div class="row mb-3">
                        <div class="col-md-4">
                          <strong>Anggota</strong>
                        </div>
                        <div class="col-md-8">
                          <span class="bullet"></span>&nbsp;{{ $kelompok->m_ketua->nama }} (Ketua)<br>
                          @foreach ($kelompok->anggota as $anggota)
                            <span
                              class="bullet"></span>&nbsp;{{ App\Models\User::where('kode', $anggota)->first()->nama }}
                            <br>
                          @endforeach
                        </div>
                      </div>
                      <div class="row mb-3">
                        <div class="col-md-4">
                          <strong>Shift</strong>
                        </div>
                        <div class="col-md-8">
                          {{ $kelompok->shift }} ({{ $kelompok->jam }})
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            </div>
          @endif
        @endif
        @if ($pinjam->bahan)
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
        @endif
      </div>
    </div>
  </section>
@endsection
