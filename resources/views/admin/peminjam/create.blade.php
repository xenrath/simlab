@extends('layouts.app')

@section('title', 'Tambah Peminjam')

@section('content')
<section class="section">
  <div class="section-header">
    <div class="section-header-back">
      <a href="{{ url('admin/peminjam') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
      </a>
    </div>
    <h1>Peminjam</h1>
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
        <h4>Tambah Peminjam</h4>
      </div>
      <form action="{{ url('admin/peminjam') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="username">NIM *</label>
                <input type="text" name="username" id="username" class="form-control" value="{{ old('username') }}">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="nama">Nama Lengkap *</label>
                <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama') }}">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="subprodi_id">Prodi *</label>
                <select class="form-control selectric" name="subprodi_id" id="subprodi_id">
                  <option value="" {{ old('subprodi_id')=='' ? 'selected' : '' }}>- Pilih Prodi -</option>
                  @foreach ($subprodis as $subprodi)
                  <option value="{{ $subprodi->id }}" {{ old('subprodi_id')==$subprodi->id ? 'selected' : '' }}>{{
                    $subprodi->jenjang }} {{ $subprodi->nama
                    }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="semester">Semester *</label>
                <select class="form-control selectric" name="semester" id="semester">
                  <option value="" {{ old('semester')=='' ? 'selected' : '' }}>- Pilih Semester -</option>
                  <option value="1" {{ old('semester')=='1' ? 'selected' : '' }}>1</option>
                  <option value="2" {{ old('semester')=='2' ? 'selected' : '' }}>2</option>
                  <option value="3" {{ old('semester')=='3' ? 'selected' : '' }}>3</option>
                  <option value="4" {{ old('semester')=='4' ? 'selected' : '' }}>4</option>
                  <option value="5" {{ old('semester')=='5' ? 'selected' : '' }}>5</option>
                  <option value="6" {{ old('semester')=='6' ? 'selected' : '' }}>6</option>
                  <option value="7" {{ old('semester')=='7' ? 'selected' : '' }}>7</option>
                  <option value="8" {{ old('semester')=='8' ? 'selected' : '' }}>8</option>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="telp">No. Telepon (opsional)</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <div class="input-group-text">+62</div>
                  </div>
                  <input type="text" class="form-control" name="telp" id="telp" value="{{ old('telp') }}">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="alamat">Alamat (opsional)</label>
                <textarea name="alamat" id="alamat" cols="30" rows="10"
                  class="form-control">{{ old('alamat') }}</textarea>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="foto">Foto (opsional)</label>
                <input type="file" name="foto" id="foto" class="form-control" value="{{ old('foto') }}"
                  accept="image/*">
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer float-right">
          <button type="submit" class="btn btn-primary">
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