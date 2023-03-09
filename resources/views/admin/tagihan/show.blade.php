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
        <form action="{{ url('admin/tagihan/konfirmasi/' . $pinjam->id) }}" method="POST">
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
                  @if ($rusak)
                    @foreach ($rusak->detail_pinjams as $rusak)
                      @if ($rusak->rusak > 0)
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
                      @endif
                    @endforeach
                  @endif
                  @if ($hilang)
                    @foreach ($hilang->detail_pinjams as $hilang)
                      @if ($hilang->hilang > 0)
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
                      @endif
                    @endforeach
                  @endif
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
