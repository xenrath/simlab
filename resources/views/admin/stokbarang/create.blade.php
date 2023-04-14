@extends('layouts.app')

@section('title', 'Tambah Stok')

@section('content')
  <section class="section">
    <div class="section-header">
      <div class="section-header-back">
        <a href="{{ url('admin/stokbarang') }}" class="btn btn-secondary">
          <i class="fas fa-arrow-left"></i>
        </a>
      </div>
      <h1>Data Stok</h1>
    </div>
    @if (session('error'))
      <div class="alert alert-danger alert-dismissible show fade">
        <div class="alert-body">
          <div class="alert-title">GAGAL !</div>
          <button class="close" data-dismiss="alert">
            <span>&times;</span>
          </button>
          <p>
            @foreach (session('error') as $error)
              <span class="bullet"></span>&nbsp;{{ $error }}
              <br>
            @endforeach
          </p>
        </div>
      </div>
    @endif
    <div class="section-body">
      <div class="card">
        <div class="card-header">
          <h4>Tambah Stok</h4>
        </div>
        <form action="{{ url('admin/stokbarang') }}" method="POST" autocomplete="off">
          @csrf
          <div class="card-body">
            <div class="form-group">
              <label for="barang_id">Nama Barang</label>
              <select class="form-control select2" id="barang_id" name="barang_id">
                @foreach ($barangs as $barang)
                  <option value="{{ $barang->id }}" {{ old('barang_id') == $barang->id ? 'selected' : '' }}>
                    {{ $barang->ruang->nama }} | {{ $barang->nama }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="normal">Jumlah Baik</label>
              <input type="number" name="normal" id="normal" class="form-control" value="{{ old('normal') }}"
                oninput="this.value = !!this.value && Math.abs(this.value) >= 0 && !!this.value ? Math.abs(this.value) : null">
            </div>
            <div class="form-group">
              <label for="rusak">Jumlah Rusak</label>
              <input type="number" name="rusak" id="rusak" class="form-control" value="{{ old('rusak') }}"
                oninput="this.value = !!this.value && Math.abs(this.value) >= 0 && !!this.value ? Math.abs(this.value) : null">
            </div>
          </div>
          <div class="card-footer float-right">
            <button type="reset" class="btn btn-secondary mr-1">
              <i class="fas fa-undo"></i> Reset
            </button>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save"></i> Simpan
            </button>
          </div>
        </form>
      </div>
    </div>
  </section>
@endsection
