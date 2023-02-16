@extends('layouts.app')

@section('title', 'Pengembalian')

@section('content')
<section class="section">
  <div class="section-header">
    <div class="section-header-back">
      <a href="{{ url('laboran/pengembalian') }}" class="btn btn-secondary">
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
      <form action="{{ url('laboran/pengembalian/konfirmasi/proses/' . $pinjam->id) }}" method="POST">
        @csrf
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th class="text-center">No.</th>
                  <th>Nama Barang</th>
                  <th class="text-center">Jumlah</th>
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
                  <td class="text-center">{{ $detail_pinjam->jumlah }}</td>
                  @php
                  $jumlah = $detail_pinjam->jumlah;
                  @endphp
                  <td>
                    <input type="number" name="normal-{{ $detail_pinjam->id }}" class="form-control"
                      oninput="this.value = !!this.value && Math.abs(this.value) >= 0 && !!this.value && Math.abs(this.value) <= {{ $jumlah }} ? Math.abs(this.value) : null"
                      value="{{ old('normal-' . $detail_pinjam->id, 0) }}" required>
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
            <i class="fas fa-undo"></i> Reset
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-paper-plane"></i> Konfirmasi
          </button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection