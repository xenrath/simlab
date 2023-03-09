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
          <h4>Konfirmasi Pengembalian</h4>
        </div>
        <form action="{{ url('laboran/pengembalian-new/' . $pinjam->id . '/p_konfirmasi') }}" method="POST">
          @csrf
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th class="text-center">No.</th>
                    <th>Nama Alat</th>
                    <th>Jumlah</th>
                    <th>Normal</th>
                    <th>Rusak</th>
                    <th>Hilang</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($detail_pinjams as $detail_pinjam)
                    <tr>
                      <td class="text-center">{{ $loop->iteration }}</td>
                      <td>{{ $detail_pinjam->barang->nama }}</td>
                      <td>{{ $detail_pinjam->jumlah }} {{ $detail_pinjam->satuan->singkatan }}</td>
                      @php
                        $jumlah = $detail_pinjam->jumlah;
                      @endphp
                      <td>
                        <input type="number" name="normal-{{ $detail_pinjam->id }}" class="form-control"
                          oninput="this.value = !!this.value && Math.abs(this.value) >= 0 && !!this.value && Math.abs(this.value) <= {{ $jumlah }} ? Math.abs(this.value) : null"
                          value="{{ old('normal-' . $detail_pinjam->id, $detail_pinjam->jumlah) }}" required>
                      </td>
                      <td>
                        <input type="number" name="rusak-{{ $detail_pinjam->id }}" class="form-control"
                          oninput="this.value = !!this.value && Math.abs(this.value) >= 0 && !!this.value && Math.abs(this.value) <= {{ $jumlah }} ? Math.abs(this.value) : null"
                          value="0" required>
                      </td>
                      <td>
                        <input type="number" name="hilang-{{ $detail_pinjam->id }}" class="form-control"
                          oninput="this.value = !!this.value && Math.abs(this.value) >= 0 && !!this.value && Math.abs(this.value) <= {{ $jumlah }} ? Math.abs(this.value) : null"
                          value="0" required>
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
      <div class="card">
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
      </div>
    </div>
  </section>
@endsection
