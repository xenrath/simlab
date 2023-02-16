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
                    <th>Nama Barang</th>
                    <th>Jumlah Rusak</th>
                    <th>Kategori</th>
                    <th>Jumlah yang dikembalikan</th>
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
                            <div class="input-group">
                              <input type="number" name="rusak-{{ $rusak->id }}" class="form-control"
                                oninput="this.value = !!this.value && Math.abs(this.value) >= 0 && !!this.value && Math.abs(this.value) <= {{ $jumlah }} ? Math.abs(this.value) : null"
                                value="{{ old('rusak-' . $rusak->id, 0) }}" required>
                              <select class="custom-select">
                                <option>pcs</option>
                              </select>
                            </div>
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
                            <div class="input-group">
                              <input type="number" name="hilang-{{ $hilang->id }}" class="form-control"
                                oninput="this.value = !!this.value && Math.abs(this.value) >= 0 && !!this.value && Math.abs(this.value) <= {{ $jumlah }} ? Math.abs(this.value) : null"
                                value="{{ old('hilang-' . $hilang->id, 0) }}" required>
                              <select class="custom-select">
                                <option>pcs</option>
                              </select>
                            </div>
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
