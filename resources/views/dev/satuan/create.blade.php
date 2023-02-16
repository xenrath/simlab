@extends('layouts.app')

@section('title', 'Tambah Satuan')

@section('content')
<section class="section">
  <div class="section-header">
    <div class="section-header-back">
      <a href="{{ url('dev/satuan') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
      </a>
    </div>
    <h1>Satuan</h1>
  </div>
  <div class="section-body">
    <div class="card">
      <div class="card-header">
        <h4>Tambah Satuan</h4>
      </div>
      <form action="{{ url('dev/satuan') }}" method="POST" autocomplete="off">
        @csrf
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="nama">Nama Satuan *</label>
                <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror"
                  value="{{ old('nama') }}">
                @error('nama')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="singkatan">Singkatan *</label>
                <input type="text" name="singkatan" id="singkatan" class="form-control @error('singkatan') is-invalid @enderror"
                  value="{{ old('singkatan') }}">
                @error('singkatan')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="kali">Kali *</label>
                <input type="number" name="kali" id="kali" class="form-control @error('kali') is-invalid @enderror"
                  value="{{ old('kali') }}">
                @error('kali')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-success">
            <i class="fas fa-paper-plane"></i> Save
          </button>
          <button type="reset" class="btn btn-secondary">
            <i class="fas fa-undo"></i> Reset
          </button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection