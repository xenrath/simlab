@extends('layouts.app')

@section('title', 'Pengembalian')

@section('content')
  <section class="section">
    <div class="section-header">
      <div class="section-header-back">
        <a href="{{ url('admin/peminjaman') }}" class="btn btn-secondary">
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
        <form action="{{ url('admin/peminjaman/konfirmasi_selesai/' . $pinjam->id) }}" method="POST">
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
                      <td class="text-center align-top py-3">{{ $loop->iteration }}</td>
                      <td class="align-top py-3">{{ $detail_pinjam->barang->nama }}</td>
                      <td cl>{{ $detail_pinjam->jumlah }} {{ $detail_pinjam->satuan->singkatan }}</td>
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
          <div class="card-header-action">
            @php
              $tanggal_awal = date('d M Y', strtotime($pinjam->tanggal_awal));
              $tanggal_akhir = date('d M Y', strtotime($pinjam->tanggal_akhir));
              $jam_awal = $pinjam->jam_awal;
              $jam_akhir = $pinjam->jam_akhir;
              $now = Carbon\Carbon::now();
              $expire = date('Y-m-d', strtotime($pinjam->tanggal_akhir));
            @endphp
            @if ($pinjam->status == 'disetujui')
              @if ($now > $expire)
                <div class="badge badge-danger">Kadaluarsa</div>
              @else
                <div class="badge badge-primary">Aktif</div>
              @endif
            @elseif ($pinjam->status == 'selesai')
              <div class="badge badge-success">Selesai</div>
            @endif
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="row mb-3">
                <div class="col-md-4">
                  <strong>Instansi</strong>
                </div>
                <div class="col-md-8">
                  {{ $pinjam->peminjam->alamat }}
                </div>
              </div>
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
                  <strong>No. Telepon</strong>
                </div>
                <div class="col-md-8">
                  +62{{ $pinjam->peminjam->telp }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
