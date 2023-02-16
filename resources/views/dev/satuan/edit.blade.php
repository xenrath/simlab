@extends('layouts.app')

@section('title', 'Ubah Prodi')

@section('content')
<section class="section">
  <div class="section-header">
    <div class="section-header-back">
      <a href="{{ url('dev/prodi') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
      </a>
    </div>
    <h1>Prodi</h1>
  </div>
  <div class="section-body">
    <div class="card">
      <div class="card-header">
        <h4>Ubah Prodi</h4>
      </div>
      <form action="{{ url('dev/prodi/' . $prodi->id) }}" method="POST" autocomplete="off">
        @csrf
        @method('put')
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="jenjang">Jenjang *</label>
                <select class="form-control selectric @error('jenjang') is-invalid @enderror" name="jenjang" id="jenjang"
                  required>
                  <option value="" {{ old('jenjang')=='' ? 'selected' : '' }}>- Pilih Prodi -</option>
                  <option value="D3" {{ old('jenjang', $prodi->jenjang)=='D3' ? 'selected' : '' }}>D3</option>
                  <option value="D4" {{ old('jenjang', $prodi->jenjang)=='D4' ? 'selected' : '' }}>D4</option>
                  <option value="S1" {{ old('jenjang', $prodi->jenjang)=='S1' ? 'selected' : '' }}>S1</option>
                  <option value="Profesi" {{ old('jenjang', $prodi->jenjang)=='Profesi' ? 'selected' : '' }}>Profesi</option>
                </select>
                @error('jenjang')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="nama">Nama Program Studi *</label>
                <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror"
                  value="{{ old('nama', $prodi->nama) }}">
                @error('nama')
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