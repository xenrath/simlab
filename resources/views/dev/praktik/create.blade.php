@extends('layouts.app')

@section('title', 'Tambah Praktik')

@section('content')
  <section class="section">
    <div class="section-header">
      <div class="section-header-back">
        <a href="{{ url('dev/praktik') }}" class="btn btn-secondary">
          <i class="fas fa-arrow-left"></i>
        </a>
      </div>
      <h1>Praktik</h1>
    </div>
    <div class="section-body">
      <div class="card">
        <div class="card-header">
          <h4>Tambah Praktik</h4>
        </div>
        <form action="{{ url('dev/praktik') }}" method="POST" autocomplete="off">
          @csrf
          <div class="card-body">
            <div class="form-group">
              <label for="nama">Nama Praktik *</label>
              <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama') }}">
            </div>
          </div>
          <div class="card-footer text-right">
            <button type="reset" class="btn btn-secondary">
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
