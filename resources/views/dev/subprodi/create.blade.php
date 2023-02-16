@extends('layouts.app')

@section('title', 'Tambah Prodi')

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
  @if (session('status'))
  <div class="alert alert-danger alert-has-icon alert-dismissible show fade">
    <div class="alert-icon">
      <i class="fas fa-exclamation-circle"></i>
    </div>
    <div class="alert-body">
      <div class="alert-title">Error!</div>
      <button class="close" data-dismiss="alert">
        <span>&times;</span>
      </button>
      <p>
        @foreach (session('status') as $error)
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
        <h4>Tambah Prodi</h4>
      </div>
      <form action="{{ url('dev/prodi') }}" method="POST" autocomplete="off">
        @csrf
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="mainprodi_id">Main Prodi *</label>
                <select class="form-control selectric @error('mainprodi_id') is-invalid @enderror" name="mainprodi_id"
                  id="mainprodi_id">
                  <option value="" {{ old('mainprodi_id')=='' ? 'selected' : '' }}>- Pilih -</option>
                  @foreach ($mainprodis as $mainprodi)
                  <option value="{{ $mainprodi->id }}" {{ old('mainprodi_id')==$mainprodi->id ? 'selected' : '' }}>{{
                    ucfirst($mainprodi->singkatan) }}</option>
                  @endforeach
                </select>
                @error('mainprodi_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="jenjang">Jenjang *</label>
                <select class="form-control selectric @error('jenjang') is-invalid @enderror" name="jenjang"
                  id="jenjang">
                  <option value="" {{ old('jenjang')=='' ? 'selected' : '' }}>- Pilih Prodi -</option>
                  <option value="D3" {{ old('jenjang')=='D3' ? 'selected' : '' }}>D3</option>
                  <option value="D4" {{ old('jenjang')=='D4' ? 'selected' : '' }}>D4</option>
                  <option value="S1" {{ old('jenjang')=='S1' ? 'selected' : '' }}>S1</option>
                  <option value="Profesi" {{ old('jenjang')=='Profesi' ? 'selected' : '' }}>Profesi</option>
                </select>
                @error('jenjang')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="nama">Nama Program Studi *</label>
                <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror"
                  value="{{ old('nama') }}">
                @error('nama')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer float-right">
          <button type="submit" class="btn btn-primary mr-1">
            <i class="fas fa-save"></i> Simpan
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