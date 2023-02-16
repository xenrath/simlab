@extends('layouts.app')

@section('title', 'Tambah Mata Kuliah')

@section('content')
<section class="section">
  <div class="section-header">
    <div class="section-header-back">
      <a href="{{ url('dev/matakuliah') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
      </a>
    </div>
    <h1>Mata Kuliah</h1>
  </div>
  <div class="section-body">
    <div class="card">
      <div class="card-header">
        <h4>Tambah Mata Kuliah</h4>
      </div>
      <form action="{{ url('dev/matakuliah/' . $matakuliah->id) }}" method="POST" autocomplete="off">
        @csrf
        @method('put')
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="nama">Nama Mata Kuliah *</label>
                <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror"
                  value="{{ old('nama', $matakuliah->nama) }}">
                @error('nama')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="prodi_id">Prodi *</label>
                <select class="form-control selectric @error('prodi_id') is-invalid @enderror" name="prodi_id"
                  id="prodi_id" required>
                  <option value="" {{ old('prodi_id')=='' ? 'selected' : '' }}>- Pilih Prodi -</option>
                  @foreach ($prodis as $prodi)
                  <option value="{{ $prodi->id }}" {{ old('prodi_id', $matakuliah->prodi_id)==$prodi->id ? 'selected' :
                    '' }}>{{ $prodi->nama }}</option>
                  @endforeach
                </select>
                @error('prodi_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="semester">Semester *</label>
                <select class="form-control selectric @error('semester') is-invalid @enderror" name="semester"
                  id="semester" required>
                  <option value="" {{ old('semester')=='' ? 'selected' : '' }}>- Pilih Semester -</option>
                  <option value="1" {{ old('semester', $matakuliah->semester)=='1' ? 'selected' : '' }}>1</option>
                  <option value="2" {{ old('semester', $matakuliah->semester)=='2' ? 'selected' : '' }}>2</option>
                  <option value="3" {{ old('semester', $matakuliah->semester)=='3' ? 'selected' : '' }}>3</option>
                  <option value="4" {{ old('semester', $matakuliah->semester)=='4' ? 'selected' : '' }}>4</option>
                  <option value="5" {{ old('semester', $matakuliah->semester)=='5' ? 'selected' : '' }}>5</option>
                  <option value="6" {{ old('semester', $matakuliah->semester)=='6' ? 'selected' : '' }}>6</option>
                  <option value="7" {{ old('semester', $matakuliah->semester)=='7' ? 'selected' : '' }}>7</option>
                  <option value="8" {{ old('semester', $matakuliah->semester)=='8' ? 'selected' : '' }}>8</option>
                </select>
                @error('semester')
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