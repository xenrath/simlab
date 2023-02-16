@extends('layouts.app')

@section('title', 'Pengembalian')

@section('content')
  <section class="section">
    <div class="section-header">
      <div class="section-header-back">
        <a href="{{ url('laboran/kelompok/pengembalian') }}" class="btn btn-secondary">
          <i class="fas fa-arrow-left"></i>
        </a>
      </div>
      <h1>Detail Pinjaman</h1>
    </div>
    <div class="section-body">
      <div class="card">
        <div class="card-header">
          <h4>Konfirmasi Barang</h4>
          <small>(kosongkan kelompok jika tidak ada barang rusak atau hilang)</small>
        </div>
        <form action="{{ url('laboran/kelompok/pengembalian/konfirmasi_pengembalian/' . $pinjam->id) }}" method="POST"
          autocomplete="off">
          @csrf
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th class="text-center">No.</th>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Normal</th>
                    <th>Rusak</th>
                    <th>Hilang</th>
                    <th>Kelompok</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($detailpinjams as $detailpinjam)
                    <tr>
                      <td class="text-center">{{ $loop->iteration }}</td>
                      <td>
                        {{ $detailpinjam->barang->nama }} <br>
                        <strong>({{ $detailpinjam->barang->ruang->nama }})</strong>
                      </td>
                      <td class="text-center">{{ $detailpinjam->jumlah }} {{ $detailpinjam->satuan->singkatan }}</td>
                      @php
                        $jumlah = $detailpinjam->jumlah;
                      @endphp
                      <td>
                        <input type="number" name="normal-{{ $detailpinjam->id }}" class="form-control"
                          oninput="this.value = !!this.value && Math.abs(this.value) >= 0 && !!this.value && Math.abs(this.value) <= {{ $jumlah }} ? Math.abs(this.value) : 0"
                          value="{{ old('normal-' . $detailpinjam->id, $detailpinjam->jumlah) }}" required>
                      </td>
                      <td>
                        <input type="number" name="rusak-{{ $detailpinjam->id }}" class="form-control"
                          oninput="this.value = !!this.value && Math.abs(this.value) >= 0 && !!this.value && Math.abs(this.value) <= {{ $jumlah }} ? Math.abs(this.value) : null"
                          value="0" required>
                      </td>
                      <td>
                        <input type="number" name="hilang-{{ $detailpinjam->id }}" class="form-control"
                          oninput="this.value = !!this.value && Math.abs(this.value) >= 0 && !!this.value && Math.abs(this.value) <= {{ $jumlah }} ? Math.abs(this.value) : null"
                          value="0" required>
                      </td>
                      <td>
                        <select name="kelompok_id-{{ $detailpinjam->id }}" class="form-control selectric">
                          <option value="">- Pilih -</option>
                          @foreach ($kelompoks as $kelompok)
                            <option value="{{ $kelompok->id }}">{{ $kelompok->nama }}</option>
                          @endforeach
                        </select>
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
            <button type="submit" class="btn btn-primary">Konfirmasi</button>
          </div>
        </form>
      </div>
      <div class="card">
        <div class="card-header">
          <h4>Detail Peminjaman</h4>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="row mb-3">
                <div class="col-md-4">
                  <strong>Waktu Awal</strong>
                </div>
                <div class="col-md-8">
                  {{ date('d M Y', strtotime($pinjam->tanggal_awal)) }}
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-4">
                  <strong>Waktu Akhir</strong>
                </div>
                <div class="col-md-8">
                  {{ date('d M Y', strtotime($pinjam->tanggal_akhir)) }}
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
                  {{ $pinjam->keterangan }}
                </div>
              </div>
            </div>
          </div>
        </div>
        <hr>
        <div class="card-body">
          <div class="row">
            @foreach ($kelompoks as $kelompok)
              <div class="col-md-6">
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
                      <span class="bullet"></span>&nbsp;{{ App\Models\User::where('kode', $anggota)->first()->nama }}
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
        <hr>
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
    </div>
  </section>
@endsection
